<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {
        $tasks = Task::orderBy('name')->paginate(20);
        return view('task.index', ['tasks' => $tasks]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create()
    {
        return view('task.create',['task' => \App\Task::all()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        Task::create($request->all());

        if($request->ajax()){
            return Task::where('group_id', $request->get('group_id'))->get();
        } else {
            return redirect()->route('task.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  $id
     * @return View
     */
    public function show($id)
    {
        return view('task.index', ['tasks' => Task::where('id', $id)->paginate(),'task' => \App\Task::all()]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Task  $task
     * @return View
     */
    public function edit(Task $task)
    {
        return view ('task.create' , ['task' => $task,'task' => \App\Task::all()]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  Task  $task
     * @return RedirectResponse
     */
    public function update(Request $request, Task $task)
    {
        $task->fill($request->except('_method', '_token'))->save();
        return redirect()->route('task.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Task  $task
     * @return RedirectResponse
     */
    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('task.index');
    }

    public function find(Request $request, $search = null)
    {
        $string = $search ?? $request->get('string');

        $data = Task::where('name', 'like', '%' . $string . '%');

        if ($search !== false && !empty($search)) {
            return view('task.index', ['tasks' => $data->paginate(20), 'search' => $string]);
        }

        return $data->take(10)->get();
    }

    public function get($task)
    {
        return Task::where('group_id', $task)->get();
    }

    public function deleteMany(Request $request)
    {
        try {
            Task::where('id', $request->get('id'))->delete();

            return Task::where('group_id', $request->get('group_id'))->get();

        } catch (Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }
}
