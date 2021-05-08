@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Create') }} {{ __('Task') }}</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    <form action="{{ isset($task) ? route('task.update', $task->id) : route('task.store') }}" method="post">
                        @method( isset($task) ? 'put' : 'post')
                        @csrf

                                                    <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $task->name ?? old('name') }}" autocomplete="name" >

                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="description" class="col-md-4 col-form-label text-md-right">{{ __('Description') }}</label>

                                <div class="col-md-6">
                                    <textarea id="description" type="text" class="form-control @error('description') is-invalid @enderror" name="description" autocomplete="description" >{{ $task->description ?? old('description') }}</textarea>

                                    @error('description')
                                    <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                    @enderror
                                </div>
                            </div>
                        <div class="form-group row">
                            <div class="col-md-4"></div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input id="selectedCheck" type="checkbox" class="form-check-input" name="selected" @if($task->selected && $task->selected === 1) checked @endif>
                                    <label class="form-check-label" for="selectedCheck">{{ __('Selected') }}</label>
                                </div>
                            </div>
                        </div>
                            <div class="form-group row">
                                <label for="position" class="col-md-4 col-form-label text-md-right">{{ __('Position') }}</label>

                                <div class="col-md-6">
                                    <input id="position" type="number" class="form-control @error('position') is-invalid @enderror" name="position" value="{{ $task->position ?? old('position') }}" autocomplete="position" >

                                    @error('position')
                                    <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="group_id" class="col-md-4 col-form-label text-md-right">{{ __('Group') }}</label>

                                <div class="col-md-6">
                                    <select class="form-control" name="group_id" id="group_id" >
                                        <option value="" disabled selected>{{ __('Select your option') }}</option>
                                        @foreach($groups as $item)
                                            <option value="{{ $item->id }}"
                                                    @if(isset($task) && $item->id === $task->group_id) selected @endif>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                        <div class="form-group row mt-3">
                            <div class="col-md-4"></div>
                            <div class="col-md-6">
                                @if(isset($task))
                                <button class="btn btn-outline-success" type="submit">
                                    <i class="fa fa-save"></i> {{ __('Update') }}</button>
                                @else
                                <button class="btn btn-outline-success" type="submit">
                                    <i class="fa fa-save"></i> {{ __('Save') }}</button>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
