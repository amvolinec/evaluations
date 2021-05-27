<?php

namespace App\Http\Controllers;

use App\Scenario;
use Illuminate\Http\Request;

class ScenarioController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            return Scenario::with('groups')->get();
        }
        return view('scenario.index');
    }
}
