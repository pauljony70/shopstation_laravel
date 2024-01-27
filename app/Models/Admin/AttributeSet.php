<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributeSet extends Model
{
    use HasFactory;

    protected $table = 'attribute_set';

    protected $fillable = [
        'name',
        'status',
        'created_by',
    ];

    public function attributeSetAttributes()
    {
        return $this->belongsToMany(AttributeSetProductInfo::class, 'attribute_set_attributes', 'attribute_set_id', 'attribute_id');
    }
}
