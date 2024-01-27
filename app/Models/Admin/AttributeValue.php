<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributeValue extends Model
{
    use HasFactory;

    protected $table = 'product_attributes_conf';

    protected $fillable = [
        'attribute_id',
        'attribute_value',
    ];
}
