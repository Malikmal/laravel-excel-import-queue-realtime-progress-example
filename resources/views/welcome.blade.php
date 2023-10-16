<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/daisyui@3.9.2/dist/full.css" rel="stylesheet" type="text/css" />
        <script src="https://cdn.tailwindcss.com"></script>

        <title>Laravel</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])

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
                    {{ session()->get('success') }}
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
                <div id="percentage"></div>
            </form>
            <table class="table">
                <tr>
                    <th>Time</th>
                    <th>File Name</th>
                    <th>Status</th>
                </tr>
                @foreach ($imports as $import)
                    <tr>
                        <td>{{ $import->created_at }} <br> ({{ $import->created_at->diffForHumans() }})</td>
                        <td>{{ $import->file_name }}</td>
                        <td>
                            {{ $import->status_text }}

                            @if ($import->status === \App\Enums\ImportStatusEnum::processing->value)
                                <span id="percentage" data-id={{ $import->id }}>{{ $import->percentage }}%</span>
                            @endif

                        </td>
                        {{-- <td id="percentage" data-id={{ $import->id }}>{{ $import->percentage }}%</td> --}}
                    </tr>
                @endforeach
            </table>
        </main>
    </body>
</html>
