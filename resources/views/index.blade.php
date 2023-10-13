<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Upload Form</title>
    <style>
        #drop-zone {
            border: 2px dashed #ccc;
            border-radius: 20px;
            padding: 20px;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
    </style>
</head>
<body>
    <div>
        <h1>File Upload</h1>
        <div id="drop-zone">
            <form id="upload-form" action="{{route('batch_upload.store')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div>
                    <label for="file">Choose a file or drag it here:</label>
                    <input type="file" id="file" name="document" accept=".csv, .xlsx" multiple>
                </div>
                <button type="submit">Upload</button>
            </form>
        </div>
    </div>

    <div>
        <h2>Uploaded Files</h2>
        <table>
            <thead>
                <tr>
                    <th>Time</th>
                    <th>File Name</th>
                    <th>Status</th>
                </tr>
                @foreach($batch_uplods as $batch_uplod)
                 <tr>
                    <th>{{date('Y F d H:i:s', strtotime($batch_uplod->created_at))}}</th>
                    <th>{{$batch_uplod->file_name}}</th>
                    <th>{{$batch_uplod->status}}</th>
                 </tr>
                 @endforeach
            </thead>
            <tbody>
                <!-- Display uploaded files in this table -->
            </tbody>
        </table>
    </div>
</body>
</html>
