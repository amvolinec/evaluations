<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Specification extends Model
{
    protected $fillable = [
        'body', 'evaluation_id'
    ];

    public function evaluation() {
        return $this->belongsTo('App\Evaluation');
    }
}
