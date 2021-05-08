<?php


namespace App\Http\View\Composers;


use App\Group;
use Illuminate\View\View;

class GroupsComposer
{
    public function compose(View $view)
    {
        return $view->with('groups', Group::orderBy('name')->get());
    }
}
