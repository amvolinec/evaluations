<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'position'
    ];

    /**
     * Get the comments for the blog post.
     */
    public function tasks()
    {
        return $this->hasMany('App\Task');
    }

    public function scenarios() {
        return $this->belongsToMany('App\Scenario');
    }
}
