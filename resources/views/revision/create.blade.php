@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ __('Create') }} {{ __('Revision') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form
                            action="{{ isset($revision) ? route('revision.update', $revision->id) : route('revision.store') }}"
                            method="post">
                            @method( isset($revision) ? 'put' : 'post')
                            @csrf

                            <div class="form-group row">
                                <label for="version"
                                       class="col-md-4 col-form-label text-md-right">{{ __('Version') }}</label>

                                <div class="col-md-6">
                                    <input id="version" type="number"
                                           class="form-control @error('version') is-invalid @enderror" name="version"
                                           value="{{ $revision->version ?? old('version') }}" autocomplete="version">

                                    @error('version')
                                    <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="evaluation_id"
                                       class="col-md-4 col-form-label text-md-right">{{ __('Evaluation') }}</label>

                                <div class="col-md-6">
                                    <select class="form-control" name="evaluation_id" id="evaluation_id">
                                        <option value="" disabled selected>{{ __('Select your option') }}</option>
                                        @foreach($evaluations as $item)
                                            <option value="{{ $item->id }}"
                                                    @if(isset($revision) && $item->id === $revision->evaluation_id) selected @endif>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                                <div class="col-md-6">
                                    <input id="name" type="text"
                                           class="form-control @error('name') is-invalid @enderror" name="name"
                                           value="{{ $revision->name ?? old('name') }}" autocomplete="name">

                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="description"
                                       class="col-md-4 col-form-label text-md-right">{{ __('Description') }}</label>

                                <div class="col-md-6">
                                    <input id="description" type="text"
                                           class="form-control @error('description') is-invalid @enderror"
                                           name="description" value="{{ $revision->description ?? old('description') }}"
                                           autocomplete="description">

                                    @error('description')
                                    <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="time" class="col-md-4 col-form-label text-md-right">{{ __('Time') }}</label>

                                <div class="col-md-6">
                                    <input id="time" type="number"
                                           class="form-control @error('time') is-invalid @enderror" name="time"
                                           value="{{ $revision->time ?? old('time') }}" autocomplete="time">

                                    @error('time')
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
                                        <input id="selectedCheck" type="checkbox" class="form-check-input"
                                               name="selected"
                                               @if(isset($revision) && $revision->selected && $revision->selected === 1) checked @endif>
                                        <label class="form-check-label" for="selectedCheck">{{ __('Selected') }}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="group_id"
                                       class="col-md-4 col-form-label text-md-right">{{ __('Group') }}</label>

                                <div class="col-md-6">
                                    @include('partials.groups.select', ['$field' => 'group_id', 'selected' => 'revision'])
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="user_id"
                                       class="col-md-4 col-form-label text-md-right">{{ __('User') }}</label>

                                <div class="col-md-6">
                                    <select class="form-control" name="user_id" id="user_id">
                                        <option value="" disabled selected>{{ __('Select your option') }}</option>
                                        @foreach($users as $item)
                                            <option value="{{ $item->id }}"
                                                    @if(isset($revision) && $item->id === $revision->user_id) selected @endif>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            <div class="form-group row mt-3">
                                <div class="col-md-4"></div>
                                <div class="col-md-6">
                                    @if(isset($revision))
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
