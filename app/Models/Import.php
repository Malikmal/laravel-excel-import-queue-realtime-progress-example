<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Import extends Model
{
    use HasFactory,
        SoftDeletes,
        HasUuids;

    const FILE_PATH = '/import';

    protected $fillable = [
        'file_name',
        'status',
        'file_path',
    ];
}
