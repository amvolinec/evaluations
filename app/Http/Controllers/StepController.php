<?php

namespace App\Http\Controllers;

use App\Group;
use App\Step;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Http\Requests\AssociateRequest;
use App\Http\Requests\StepRequest;
use Illuminate\Http\Response;

class StepController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {
        $steps = Step::paginate(20);
        return view('step.index', ['steps' => $steps]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create()
    {
        return view('step.create',['groups' => Group::all()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StepRequest $request
     * @return RedirectResponse
     */
    public function store(StepRequest $request)
    {
        if ($request->ajax()) {
            $task_id = $request->get('task_id');
            $step = Step::create($request->except('task_id'));
            $step->tasks()->attach($task_id);

            return $this->getStepsByTaskId($task_id);
        } else {

            Step::create($request->except('_method', '_token'));

        }
        return redirect()->route('step.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  $id
     * @return View
     */
    public function show($id)
    {
        return view('step.index', ['steps' => Step::where('id', $id)->paginate(),'group' => \App\Group::all()]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Step  $step
     * @return View
     */
    public function edit(Step $step)
    {
        return view ('step.create' , ['step' => $step,'groups' => Group::all()]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  Step  $step
     * @return RedirectResponse
     */
    public function update(Request $request, Step $step)
    {
        if ($request->ajax()) {
            $step->name = $request->get('name');
            $step->time = $request->get('time');
            $step->save();
            return response()->json($step);
        } else {
            $step->fill($request->except('_method', '_token'))->save();

        }
        return redirect()->route('step.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Step  $step
     * @return RedirectResponse
     */
    public function destroy(Step $step)
    {
        $step->delete();
        return redirect()->route('step.index');
    }

    public function associate(AssociateRequest $request)
    {
        Step::findOrfail($request->get('step_id'))->tasks()->attach($request->get('task_id'));
        return response()->json('Associated');
    }

    public function find(Request $request, $search = null)
    {
        $string = $search ?? $request->get('string');

        if (empty($string) && $request->ajax()) {
            $name = "%$request->name%";
            return Step::where('name', 'like', $name)->orderBy('name')->take(10)->get();
        }


        $data = Step::where('name', 'like', '%' . $string . '%');

        if ($search !== false && !empty($search)) {
            return view('step.index', ['steps' => $data->paginate(20), 'search' => $string]);
        }

        return $data->take(10)->get();
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
