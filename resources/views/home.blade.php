@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <div class="m-1"><h2>{{ __('Evaluation') }}</h2></div>

                <div class="m-2">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                </div>
            </div>

            <div class="container-fluid">
                <div class="row">
                    @include('groups.socket')
                </div>
            </div>

        </div>
    </div>
@endsection
