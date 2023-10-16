<?php

namespace App\Http\Controllers;

use App\Enums\ImportStatusEnum;
use App\Http\Requests\ImportRequest;
use App\Imports\ProductImport;
use App\Models\Import;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Excel as ExcelExcel;

class ImportController extends Controller
{
    public function __invoke(ImportRequest $request)
    {
        try {
            DB::beginTransaction();

            // save uploaded file
            $filePath = $request->file('file')->store(Import::FILE_PATH);

            // save imported file data
            $import = Import::create([
                'file_name' => $request->file('file')->getClientOriginalName(),
                'status' => ImportStatusEnum::pending,
                'file_path' => $filePath,
            ]);

            // import file in queue
            (new ProductImport($import))->queue(
                filePath: $filePath,
                disk: config('filesystems.default'),
                readerType: ExcelExcel::CSV,
            );

            DB::commit();

            return redirect()
                ->route('welcome')
                ->withSuccess('Processing to import');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th);
            return redirect()
                ->back()
                ->withErrors($th->getMessage());
        }
    }
}
