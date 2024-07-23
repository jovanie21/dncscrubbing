<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DncList extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone_no',
        'federal',
        'litigator',
        'internal',
        'wireless',
        'upload_path',
        'region_id',
        'uploaded_by',
        'modified_by'
    ];
}
