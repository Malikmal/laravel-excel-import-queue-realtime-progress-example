<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory,
        SoftDeletes,
        HasUuids;


    protected $fillable = [
        'unique_key',
        'product_title',
        'product_description',
        'style',
        'sanmar_mainframe_color',
        'size',
        'color_name',
        'piece_price',
    ];
}
