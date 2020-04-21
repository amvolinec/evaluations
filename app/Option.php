<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    protected $fillable = [
        'name'
    ];

    public function evaluation(){
        return $this->belongsTo('App\Evaluation');
    }
}
