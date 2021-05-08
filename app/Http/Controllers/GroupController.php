<?php

namespace App\Http\Controllers;

use App\Events\NewGroup;
use App\Group;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {
        $groups = Group::paginate(20);
        return view('group.index', ['groups' => $groups]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create()
    {
        return view('group.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse|array
     */
    public function store(Request $request)
    {
        $result = null;

        if ($request->ajax()) {
            $name = $request->get('name');
            Group::create(array('name' => $name));

            event(new NewGroup());
            $result = ['status' => 'Group added'];
        } else {
            Group::create($request->except('_method', '_token'));
            $result = redirect()->route('group.index');
        }
        return $result;

    }

    /**
     * Display the specified resource.
     *
     * @param  $id
     * @return View
     */
    public function show($id)
    {
        return view('group.index', ['groups' => Group::where('id', $id)->paginate()]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Group $group
     * @return View
     */
    public function edit(Group $group)
    {
        return view('group.create', ['group' => $group]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Group $group
     * @return RedirectResponse
     */
    public function update(Request $request, Group $group)
    {
        if ($request->ajax()) {

        } else {
            $group->fill($request->all())->save();
        }

        return redirect()->route('group.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Group $group
     * @return RedirectResponse
     */
    public function destroy(Group $group)
    {
        $group->delete();
        return redirect()->route('group.index');
    }

    public function find(Request $request, $search = false)
    {
        $string = $search ?? $request->get('string');

        $data = Group::where('name', 'like', '%' . $string . '%')// ->orWhere('title', 'like', '%' . $string . '%')
        ;

        if ($search !== false && !empty($search)) {
            return view('group.index', ['groups' => $data->paginate(20), 'search' => $string]);
        } else {
            return $data->take(10)->get();
        }
    }

    public function get()
    {
        return Group::orderBy('name')->get();
    }

    public function deleteMany(Request $request)
    {
        try {
            Group::where('id', $request->id)->delete();
            event(new NewGroup());
            return response()->json('Group deleted');
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }
}
