@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <div class="m-2">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                </div>
            </div>

            <div class="container mt-5">
                <div class="row">
                    <div class="col-md-1"></div>
                    <div class="col-md-10">
                        {{ $result }}
                    </div>
                    <div class="col-md-1"></div>
                </div>
            </div>

        </div>
    </div>
@endsection
