@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header d-flex">
                        <div class="p-2" style="vertical-align: top;">
                            <form action="{{ route('step.create') }}">
                                @method('post')
                                @csrf
                                <button class="btn btn-success"><i aria-hidden="true" class="fa fa-plus"></i></button>
                            </form>
                        </div>
                        <div style="padding: 5px;">
                            <div style=" margin: 8px 0 0 8px;position: relative;">
                                <h4 >{{ __('Steps') }}</h4>
                            </div>
                        </div>
                        <find route="step" fields="name" search="{{ $search ?? '' }}"></find>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-striped table-responsive-md">
                            <thead class="thead">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">{{ __("Name") }}</th>
								<th scope="col">{{ __("Description") }}</th>
								<th scope="col">{{ __("Time") }}</th>
								<th scope="col">{{ __("Average") }}</th>
								<th scope="col">{{ __("Count") }}</th>

                                <th scope="col" style="width: 100px;">{{ __('Actions') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($steps AS $step)
                                <tr>
                                    <th scope="row">{{ $step->id  }}</th>
                                    <td>{{ $step->name }}</td>
									<td>{{ $step->description }}</td>
									<td>{{ $step->time }}</td>
									<td>{{ $step->average > 0 ? $step->average : $step->time }}</td>
									<td>{{ $step->count }}</td>

                                    <td>
                                        <form class="float-right" action="{{ route('step.destroy', $step->id) }}"
                                              method="post" onsubmit="return confirm('Do you really want to delete?');">
                                            @method('delete')
                                            @csrf
                                            <button class="btn btn-sm btn-outline-danger" type="submit"><i class="fa fa-trash"></i></button>
                                        </form>
                                        <a class="btn btn-sm btn-outline-success float-right" style="margin: 0 8px;"
                                           href="{{ route('step.edit', $step->id) }}"><i class="fa fa-edit"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{ $steps->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
