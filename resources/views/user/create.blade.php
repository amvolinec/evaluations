@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
{{--            <div class="col-md-12">--}}

{{--                <div class="m-2">--}}
{{--                    @if (session('status'))--}}
{{--                        <div class="alert alert-success" role="alert">--}}
{{--                            {{ session('status') }}--}}
{{--                        </div>--}}
{{--                    @endif--}}

{{--                </div>--}}
{{--            </div>--}}

            <create-user></create-user>

        </div>
    </div>
@endsection
