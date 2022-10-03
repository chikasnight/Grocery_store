<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'image',
        'upload_successful',
        'disk',
    ];
    
    public function property(){
        return$this->belongsTo(User::class);
    }
}
