<?php

namespace App\Http\Controllers;

use App\Enums\ImportStatusEnum;
use App\Http\Requests\ImportRequest;
use App\Models\Import;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
                'status' => ImportStatusEnum::UPLOADED,
                'file_path' => $filePath,
            ]);

            // trigger job to import file


            DB::commit();

            return redirect()
                ->route('welcome')
                ->withSuccess('success');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th);
            return redirect()
                ->back()
                ->withErrors($th->getMessage());
        }
    }
}
