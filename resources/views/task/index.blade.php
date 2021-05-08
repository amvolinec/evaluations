@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-inline-block" style="vertical-align: top;">
                            <form action="{{ route('task.create') }}">
                                @method('post')
                                @csrf
                                <button class="btn btn-success"><i aria-hidden="true" class="fa fa-plus"></i></button>
                            </form>
                        </div>
                        <div class="d-inline-block" style="vertical-align: top; padding: 6px;">
                            <h4 >{{ __('Tasks') }}</h4>
                        </div>
                        <find route="task" fields="" search="{{ $search ?? '' }}"></find>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-striped table-responsive-md">
                            <thead class="thead">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">{{ __("Name") }}</th>
								<th scope="col">{{ __("Description") }}</th>
{{--								<th scope="col">{{ __("Selected") }}</th>--}}
								<th scope="col">{{ __("Position") }}</th>
								<th scope="col">{{ __("Group") }}</th>

                                <th scope="col" style="width: 100px;">{{ __('Actions') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($tasks AS $task)
                                <tr>
                                    <th scope="row">{{ $task->id  }}</th>
                                    <td>{{ $task->name }}</td>
									<td>{{ $task->description }}</td>
{{--									<td>{{ $task->selected }}</td>--}}
									<td>{{ $task->position }}</td>
                                    <td>{{ $task->group->name ?? '' }}</td>
                                    <td>
                                        <form class="float-right" action="{{ route('task.destroy', $task->id) }}"
                                              method="post" onsubmit="return confirm('Do you really want to delete?');">
                                            @method('delete')
                                            @csrf
                                            <button class="btn btn-sm btn-outline-danger" type="submit"><i class="fa fa-trash"></i></button>
                                        </form>
                                        <a class="btn btn-sm btn-outline-success float-right" style="margin: 0 8px;"
                                           href="{{ route('task.edit', $task->id) }}"><i class="fa fa-edit"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{ $tasks->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
