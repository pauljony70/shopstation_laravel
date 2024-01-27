<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use HasFactory;

    protected $table = 'product_attributes_set';

    protected $fillable = [
        'attribute',
        'status',
        'created_by',
    ];
}
