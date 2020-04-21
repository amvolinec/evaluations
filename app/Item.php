<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'name', 'time', 'step_id'
    ];

    public function evaluation(){
        return $this->belongsTo('App\Evaluation');
    }
}
