<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Step extends Model
{
    protected $fillable = [
        'name', 'time', 'group_id'
    ];

    public function tasks() {
        return $this->belongsToMany('App\Task', 'step_task');
    }
}
