<?php

namespace App\Http\Controllers;

use App\Revision;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RevisionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {
        $revisions = Revision::paginate(20);
        return view('revision.index', ['revisions' => $revisions]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create()
    {
        return view('revision.create',['evaluations' => \App\Evaluation::all(),'groups' => \App\Group::all(),'users' => \App\User::all()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        Revision::create($request->except('_method', '_token'));
        return redirect()->route('revision.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  $id
     * @return View
     */
    public function show($id)
    {
        return view('revision.index', ['revisions' => Revision::where('id', $id)->paginate(),'evaluations' => \App\Evaluation::all(),'groups' => \App\Group::all(),'users' => \App\User::all()]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Revision  $revision
     * @return View
     */
    public function edit(Revision $revision)
    {
        return view ('revision.create' , ['revision' => $revision,'evaluations' => \App\Evaluation::all(),'groups' => \App\Group::all(),'users' => \App\User::all()]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  Revision  $revision
     * @return RedirectResponse
     */
    public function update(Request $request, Revision $revision)
    {
        $revision->fill($request->except('_method', '_token'))->save();
        return redirect()->route('revision.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Revision  $revision
     * @return RedirectResponse
     */
    public function destroy(Revision $revision)
    {
        $revision->delete();
        return redirect()->route('revision.index');
    }

    public function find(Request $request, $search = false)
    {
        $string = $search ?? $request->get('string');

        $data = Revision::where('name', 'like', '%' . $string . '%');

        if ($search !== false && !empty($search)) {
            return view('revision.index', ['revisions' => $data->paginate(20), 'search' => $string]);
        } else {
            return $data->take(10)->get();
        }
    }
}
