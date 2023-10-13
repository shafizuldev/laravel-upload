<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BatchCollection extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'batch_upload_id',
        'unique_key',
        'product_title',
        'product_description',
        'style',
        'sanmar_mainframe_color',
        'size',
        'color_name',
        'piece_price',
        'status'
    ];
}
