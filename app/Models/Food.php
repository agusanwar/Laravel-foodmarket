<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Food extends Model
{
    use HasFactory, SoftDeletes;

     protected $fillable = [
        'name', 
        'description', 
        'ingredients',
        'price',
        'rate',
        'type',
        'picture',
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

    public function toArray()
    {
        $toArray = parent::toArray();
        $toArray['picture'] = $this->picture;
        return $toArray;
    }

    public function getPictureAtAtribute()
    {
        return url('') . Storage::url($this->atatribute['picture']);
    }

}
