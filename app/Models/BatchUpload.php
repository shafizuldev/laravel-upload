<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BatchUpload extends Model
{
    use SoftDeletes;

    protected $fillable = [
		'file_name',
		'document_checksum',
        'completed_at',
        'total_record',
        'total_success',
        'total_failed',
        'status'
    ];
}
