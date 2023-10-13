<?php

namespace App\Http\Controllers;

use App\Jobs\CollectionJob;
use App\Models\BatchUpload;
use App\Models\BatchCollection;
use App\Http\Resources\ResponseResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BatchUploadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $batch_uplods = BatchUpload::paginate();
        $data = compact('batch_uplods');

        return view('index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $file = $request->file('document');
        if($file) {
            $checksum = md5(file_get_contents($file->getPathname()));
            $exist = BatchUpload::where('document_checksum', 1)->exists();
            $file_name = $file->getClientOriginalName();

            if($exist) {
                return 'the file you upload already exist';
            } else {

                $file_path = Storage::disk('public')->putFile('uploads', $file);
                $total_records = $this->countTotalRecords($file);

                $batch_upload = BatchUpload::create([
                    'file_name' => $file_name,
                    'document_checksum' => $checksum,
                    'total_record' => $total_records,
                    'status' => 'pending'
                ]);
                $fileContent = file_get_contents($file->getRealPath());

                CollectionJob::dispatch($file_path, $batch_upload->id);
                
                return redirect()->route('batch_upload.index');
            }
            
        }
    }

    private function countTotalRecords($file)
    {
        $fileContent = file_get_contents($file->getRealPath());
        $lines = explode("\n", $fileContent);
        return count($lines);
    }

    public function readfile($fileContent, $batch_upload)
    {
        $data = array_map('str_getcsv', explode("\n", $fileContent));
        return $data;
        $headers = array_shift($data);
        foreach ($data as $row) {
            $unique_key = $row[array_search('UNIQUE_KEY', $headers)];
            $product_title = $row[array_search('PRODUCT_TITLE', $headers)];
            $product_description = $row[array_search('PRODUCT_DESCRIPTION', $headers)];
            $style_x = $row[array_search('STYLE#', $headers)];
            $sanmar_mainframe_color = $row[array_search('SANMAR_MAINFRAME_COLOR', $headers)];
            $size = $row[array_search('SIZE', $headers)];
            $color_name = $row[array_search('COLOR_NAME', $headers)];
            $piece_price = $row[array_search('PIECE_PRICE', $headers)];

            $batch_collection = BatchCollection::create([
                'batch_upload_id' => $batch_upload,
                'unique_key' => $unique_key,
                'product_title' => $product_title,
                'product_description' => $product_description,
                'style' => $style_x,
                'sanmar_mainframe_color' => $sanmar_mainframe_color,
                'size' => $size,
                'color_name' => $color_name,
                'piece_price' => $piece_price
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BatchUpload  $batchUpload
     * @return \Illuminate\Http\Response
     */
    public function show(BatchUpload $batchUpload)
    {
        $data = compact('batchUpload');
        return view('show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BatchUpload  $batchUpload
     * @return \Illuminate\Http\Response
     */
    public function edit(BatchUpload $batchUpload)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BatchUpload  $batchUpload
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BatchUpload $batchUpload)
    {
        $data = $request->all();
        $file = $request->file('document');

        if($file) {
            $checksum = md5(file_get_contents($file->getPathname()));
            
            $batch_upload = BatchUpload::where('document_checksum', $checksum)->exists();

            if($batch_upload) {
                return 'there are no change of the file';
            } else {

            }
        }

        
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BatchUpload  $batchUpload
     * @return \Illuminate\Http\Response
     */
    public function destroy(BatchUpload $batchUpload)
    {
        //
    }
}
