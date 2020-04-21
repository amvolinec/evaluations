<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    protected $fillable = [
        'name', 'date', 'client', 'task_id'
    ];

    public function items() {
        return $this->hasMany('App\Item');
    }

    public function options() {
        return $this->hasMany('App\Option');
    }
}
