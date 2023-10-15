<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/daisyui@3.9.2/dist/full.css" rel="stylesheet" type="text/css" />
        <script src="https://cdn.tailwindcss.com"></script>

        <title>Laravel</title>

        <!-- Styles -->
        <style>
        </style>
    </head>
    <body class="bg-white text-black">
        <main class="mx-auto w-full max-w-7xl flex flex-col space-y-4">
            <h1 class="m-0 text-2xl font-semibold">Import CSV</h1>
            @if($errors->any())
                <div class="alert alert-danger">
                    {{ implode('', $errors->all(':message')) }}
                </div>
            @endif
            @if(session()->has('success'))
                <div class="alert alert-success">
                    success: {{ implode('', $errors->all(':message')) }}
                </div>
            @endif
            <form action="{{ route('import') }}" method="post" enctype="multipart/form-data" class="inline-flex justify-start items-center gap-2">
                @csrf
                <div class="form-group">
                    <label for="file"></label>
                    <input type="file" name="file" class="file-input file-input-bordered file-input-primary w-full max-w-xs" />
                </div>
                <div class="form-group">
                    <button class="btn btn-primary">Import</button>
                </div>
            </form>
            <table class="table">
                <th>
                    <td>Time</td>
                    <td>File Name</td>
                    <td>Status</td>
                </th>
            </table>
        </main>
    </body>
</html>
