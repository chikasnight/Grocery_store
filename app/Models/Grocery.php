<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grocery extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'price',
        'category',
        'image',
        'upload_successful',
        'disk'
    ];
       // relationship of user & post is one to many;
    public function user(){
        return $this->belongsTo(User::class);
    }
}
