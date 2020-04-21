<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{

    protected $fillable = [
        'name', 'group_id'
    ];

    /**
     * Get the group that owns the task.
     */
    public function group()
    {
        return $this->belongsTo('App\Group');
    }

    public function steps() {
        return $this->belongsToMany('App\Step', 'step_task');
    }
}
