<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssociateRequest;
use App\Http\Requests\StepRequest;
use App\Step;
use App\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class StepController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return Response
     */
    public function store(StepRequest $request)
    {
        $task_id = $request->get('task_id');
        $step = Step::create($request->except('task_id'));
        $step->tasks()->attach($task_id);

        return $this->getStepsByTaskId($task_id);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Step $step
     * @return Response
     */
    public function show(Step $step)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Step $step
     * @return Response
     */
    public function edit(Step $step)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StepRequest $request
     * @param \App\Step $step
     * @return Response
     */
    public function update(Request $request, Step $step)
    {
        $step->name = $request->get('name');
        $step->time = $request->get('time');
        $step->save();
        return response()->json($step);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Step $step
     * @return Response
     */
    public function destroy(Step $step)
    {
        //
    }

    public function associate(AssociateRequest $request)
    {
        Step::findOrfail($request->get('step_id'))->tasks()->attach($request->get('task_id'));
        return response()->json('Associated');
    }

    public function find(Request $request)
    {
        $name = "%$request->name%";
        return Step::where('name', 'like', $name)->orderBy('name')->take(10)->get();
    }

    public function get($task_id)
    {
        return $this->getStepsByTaskId($task_id);
    }

    public function deleteMany(Request $request)
    {
        try {
            $id = $request->get('id');
            $steps = Step::where('id', $id)->withCount('tasks')->first();
            $step = Step::findOrFail($id);

//            Log::info("Step: {$id} count: " . json_encode($steps->tasks_count, JSON_PRETTY_PRINT));

            if ($steps->tasks_count == 0) {

                $step->delete();

                $message = 'Step deleted';
            } else {

                $step->tasks()->detach($request->get('task_id'));

                $message = 'Step detached';
            }

            return response()->json($message);

        } catch (Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    protected function getStepsByTaskId($task_id)
    {
        return Step::whereHas('tasks', function ($query) use ($task_id) {
            $query->where('task_id', $task_id);
        })->orderBy('name')->get();
    }
}
