<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributeSetProductInfo extends Model
{
    use HasFactory;

    protected $table = 'attribute_set_attributes';

    protected $fillable = [
        'attribute_set_id',
        'attribute_id',
    ];
}
