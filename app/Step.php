<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Step extends Model
{
    protected $fillable = ['name', 'time', 'group_id'];

    protected $appends = ['average'];

    public function tasks()
    {
        return $this->belongsToMany('App\Task', 'step_task');
    }

    public function items() {
        return $this->hasMany('App\Item');
    }

    public function getAverageAttribute()
    {
        return ceil($this->items()->pluck('time')->average());
    }

    public function getCountAttribute(){
        return $this->items()->count();
    }

    public function scenarios() {
        return $this->belongsToMany('App\Scenario');
    }
}
