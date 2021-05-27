<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Scenario extends Model
{
    protected $guarded = [];
    protected $appends = ['groups_names'];

    public function groups() {
        return $this->belongsToMany('App\Group');
    }

    public function steps() {
        return $this->belongsToMany('App\Step', 'step_scenario');
    }

    public function getGroupsNamesAttribute(){
        return $this->groups()->pluck('name')->implode(',');
    }
}
