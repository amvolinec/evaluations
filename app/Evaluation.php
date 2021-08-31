<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
//    protected $fillable = [
//        'name', 'date', 'client', 'task_id', 'version'
//    ];

    protected $guarded = [];

    public function items()
    {
        return $this->hasMany('App\Item');
    }

    public function options()
    {
        return $this->hasMany('App\Option');
    }

    public function revisions()
    {
        return $this->hasMany('App\Revision');
    }

    public function specifications()
    {
        return $this->hasMany('App\Specification');
    }

    public function getLastVersionAttribute()
    {
        return \App\Revision::selectRaw('max(version) as version')->where('evaluation_id', $this->id)->first();
    }

    public function isRevisionExist(int $version = 1)
    {
        $items = Revision::select('id')
            ->where([['evaluation_id', $this->id], ['version', $version]])
            ->first();

        return !empty($items);
    }
}
