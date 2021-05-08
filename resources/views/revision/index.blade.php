@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                        <div class="d-inline-block" style="vertical-align: top;">
                            <form action="{{ route('revision.create') }}">
                                @method('post')
                                @csrf
                                <button class="btn btn-success"><i aria-hidden="true" class="fa fa-plus"></i></button>
                            </form>
                        </div>
                        <div class="d-inline-block" style="vertical-align: top; padding: 6px;">
                            <h4 >{{ __('Revisions') }}</h4>
                        </div>
                        <find route="revision" fields="" search="{{ $search ?? '' }}"></find>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-striped table-responsive-md">
                            <thead class="thead">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">{{ __("Version") }}</th>
								<th scope="col">{{ __("Evaluation") }}</th>
								<th scope="col">{{ __("Name") }}</th>
								<th scope="col">{{ __("Description") }}</th>
								<th scope="col">{{ __("Time") }}</th>
								<th scope="col">{{ __("Selected") }}</th>
								<th scope="col">{{ __("Group") }}</th>

                                <th scope="col" style="width: 100px;">{{ __('Actions') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($revisions AS $revision)
                                <tr>
                                    <th scope="row">{{ $revision->id  }}</th>
                                    <td>{{ $revision->version }}</td>
									<td>{{ $revision->evaluation->name }}</td>
									<td>{{ $revision->name }}</td>
									<td>{{ $revision->description }}</td>
									<td>{{ $revision->time }}</td>
									<td>{{ $revision->selected }}</td>
									<td>{{ $revision->group->name }}</td>

                                    <td>
                                        <form class="float-right" action="{{ route('revision.destroy', $revision->id) }}"
                                              method="post" onsubmit="return confirm('Do you really want to delete?');">
                                            @method('delete')
                                            @csrf
                                            <button class="btn btn-sm btn-outline-danger" type="submit"><i class="fa fa-trash"></i></button>
                                        </form>
                                        <a class="btn btn-sm btn-outline-success float-right" style="margin: 0 8px;"
                                           href="{{ route('revision.edit', $revision->id) }}"><i class="fa fa-edit"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{ $revisions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
