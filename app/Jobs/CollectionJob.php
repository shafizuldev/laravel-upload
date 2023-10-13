<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\BatchCollection;
use App\Models\BatchUpload;
use Illuminate\Support\Facades\Storage;

class CollectionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $file_path;
    protected $batch_upload;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($file_path, $batch_upload)
    {
        $this->file_path = $file_path;
        $this->batch_upload = $batch_upload;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $file_content = Storage::disk('public')->get($this->file_path);
        $data = array_map('str_getcsv', explode("\n", $file_content));
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

            $existing_record = BatchCollection::where('unique_key', $unique_key)->first();

            if($existing_record) {
                $batch_collection = $existing_record->update([
                    'product_title' => $product_title,
                    'product_description' => $product_description,
                    'style' => $style_x,
                    'sanmar_mainframe_color' => $sanmar_mainframe_color,
                    'size' => $size,
                    'color_name' => $color_name,
                    'piece_price' => $piece_price,
                    'status' => 'complete'
                ]);
            } else {
                $batch_collection = BatchCollection::create([
                    'batch_upload_id' => $this->batch_upload,
                    'unique_key' => $unique_key,
                    'product_title' => $product_title,
                    'product_description' => $product_description,
                    'style' => $style_x,
                    'sanmar_mainframe_color' => $sanmar_mainframe_color,
                    'size' => $size,
                    'color_name' => $color_name,
                    'piece_price' => $piece_price,
                    'status' => 'complete'
                ]);
            }

            $batch_upload = BatchUpload::find($this->batch_upload);
            $current_total_success = $batch_upload->total_success;
            $latest_total_success = $current_total_success + 1;

            if ($latest_total_success == $batch_upload->total_records) {
                $update_data = [
                    'total_success' => $latest_total_success,
                    'status' => 'complete',
                ];
            } elseif ($batch_upload->total_records == 1) {
                $update_data = [
                    'total_success' => $latest_total_success,
                    'status' => 'processing',
                ];
            } else {
                $update_data = [
                    'total_success' => $latest_total_success,
                ];
            }

            $batch_upload->update($update_data);
        }
    }
}
