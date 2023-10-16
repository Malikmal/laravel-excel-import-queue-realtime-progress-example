<?php

namespace App\Imports;

use App\Enums\ImportStatusEnum;
use App\Events\BroadcastProgressImport;
use App\Models\Import;
use App\Models\Product;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\RemembersChunkOffset;
use Maatwebsite\Excel\Concerns\RemembersRowNumber;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithProgressBar;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeImport;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Events\ImportFailed;

class ProductImport implements
    ToModel,
    WithHeadingRow,
    WithBatchInserts,
    WithUpserts,
    WithChunkReading,
    ShouldQueue,
    WithProgressBar,
    WithEvents,
    SkipsOnError
{
    use Importable,
        RemembersRowNumber,
        RemembersChunkOffset;

    public function __construct(
        public Import $import,
        public ?int $percentage = 0,
        public ?int $totalRow = 0,
        public ?int $currentChunkOffset = 0,
    ){}

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if($this->currentChunkOffset != $this->getChunkOffset()){
            $this->currentChunkOffset = $this->getChunkOffset();

            $this->percentage = $this->getChunkOffset() / $this->totalRow * 100;

            $this->import->update(['percentage' => $this->percentage]);

            broadcast(new BroadcastProgressImport(
                import: $this->import,
                percentage: $this->percentage,
            ));
        }

        $dataOnlyFillable = Arr::only(
            array: $row,
            keys: (new Product)->getFillable(),
        );
        $dataFilteredNonUTF = Arr::map(
            array: $dataOnlyFillable,
            callback: fn($value, string $key) => mb_convert_encoding($value, 'utf-8')
        );
        $product = new Product($dataFilteredNonUTF);
        return $product;
    }

    public function getCsvSettings(): array
    {
        return [
            'input_encoding' => 'ISO-8859-1'
        ];
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function uniqueBy()
    {
        return 'unique_key';
    }

    public function registerEvents(): array
    {
        return [
            ImportFailed::class => function(ImportFailed $event) {
                $this->import->update([
                    'status' => ImportStatusEnum::failed,
                ]);
                Log::error(['import failed', $event]);
            },
            BeforeImport::class => function(BeforeImport $event) {
                // prevent race conditions
                Product::query()->lockForUpdate();

                $this->totalRow = $event->getReader()->getTotalRows()['Worksheet'];

                $this->import->update([
                    'status' => ImportStatusEnum::processing,
                    'total_rows' => $this->totalRow
                ]);

            },
            AfterImport::class => function(AfterImport $event){
                $this->percentage = 100;
                $this->import->update([
                    'status' => ImportStatusEnum::completed,
                    'percentage' => 100,
                ]);
                broadcast(new BroadcastProgressImport(
                    import: $this->import,
                    percentage: $this->percentage,
                ));
            },

        ];
    }

    /**
     * @param \Throwable $e
     */
    public function onError(\Throwable $e)
    {
        // Handle the exception how you'd like.
        $this->import->update([
            'status' => ImportStatusEnum::failed,
        ]);
        Log::error($e);
    }

}
