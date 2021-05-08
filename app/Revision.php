<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Revision extends Model
{
    protected $fillable = ["version", "evaluation_id", "name", "description", "time", "selected", "group_id", "user_id"];

    public function evaluation()
    {
        return $this->belongsTo('App\Evaluation');
    }


    public function group()
    {
        return $this->belongsTo('App\Group');
    }


    public function user()
    {
        return $this->belongsTo('App\User');
    }

	//
}
