<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EvalGroup extends Model
{
    protected $fillable = [
        'name', 'evaluation_id', 'position'
    ];

    public function evaluation() {
        return $this->belongsTo('App\Evaluation');
    }
}
