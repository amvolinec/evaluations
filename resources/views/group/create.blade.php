@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Create') }} {{ __('Group') }}</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    <form action="{{ isset($group) ? route('group.update', $group->id) : route('group.store') }}" method="post">
                        @method( isset($group) ? 'put' : 'post')
                        @csrf

                                                    <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $group->name ?? old('name') }}" autocomplete="name" >

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
                                    <textarea id="description" type="text" class="form-control @error('description') is-invalid @enderror" name="description" autocomplete="description" >{{ $group->description ?? old('description') }}</textarea>

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
                                    <input id="selectedCheck" type="checkbox" class="form-check-input" name="selected" @if($group->selected && $group->selected === 1) checked @endif>
                                    <label class="form-check-label" for="selectedCheck">{{ __('Selected') }}</label>
                                </div>
                            </div>
                        </div>
                            <div class="form-group row">
                                <label for="position" class="col-md-4 col-form-label text-md-right">{{ __('Position') }}</label>

                                <div class="col-md-6">
                                    <input id="position" type="number" class="form-control @error('position') is-invalid @enderror" name="position" value="{{ $group->position ?? old('position') }}" autocomplete="position" >

                                    @error('position')
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
                                    <input id="requiredCheck" type="checkbox" class="form-check-input" name="required" @if($group->required && $group->required === 1) checked @endif>
                                    <label class="form-check-label" for="requiredCheck">{{ __('Required') }}</label>
                                </div>
                            </div>
                        </div>


                        <div class="form-group row mt-3">
                            <div class="col-md-4"></div>
                            <div class="col-md-6">
                                @if(isset($group))
                                <button class="btn btn-outline-success" type="submit">
                                    <i class="far fa-save"></i> {{ __('Update') }}</button>
                                @else
                                <button class="btn btn-outline-success" type="submit">
                                    <i class="far fa-save"></i> {{ __('Save') }}</button>
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
