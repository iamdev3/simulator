<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Prize extends Model
{

    protected $guarded = ['id'];

    protected $fillable = [
        'title',
        'probability',
        'awarded'
    ];


    public  static function nextPrize()
    {
        // TODO: Implement nextPrize() logic here.
    }
}
