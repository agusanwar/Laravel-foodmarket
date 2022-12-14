<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name_product',
        'harga',
        'description',
    ];

     //assesor
    public function getCreatedAttribute($value)
    {
        return Carbon::parse($value)->timestamp;
    }
    //assesor
    public function getUpdateAttribute($value)
    {
        return Carbon::parse($value)->timestamp;
    }
}
