<?php

namespace App\Models;

use App\Models\User;
use App\Models\Food;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'food_id', 
        'user_id', 
        'quantity',
        'total',
        'status',
        'payment_url',
    ];

    public function food()
    {
        return $this->hasOne(Food::Class, 'id', 'food_id');
    }

    public function user()
    {
        return $this->hasOne(User::Class, 'id', 'user_id');
    }

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
