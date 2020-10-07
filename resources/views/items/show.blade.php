@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
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

            <div class="card">
                <div class="card-header">{{ $eval->name }}</div>
                <div class="card-body">
                    <p><span class="bold">Name:</span> {{ $eval->name }}</p>
                    <p><span class="bold">Date:</span> {{ $eval->date }}</p>
                    <p><span class="bold">Client:</span> {{ $eval->client }}</p>

                    <h5>Tasks:</h5>
                    <ul>
                        @foreach($eval->options as $option)
                            <li>{{ $option->name }}</li>
                        @endforeach
                    </ul>
                    <h5>Evaluations:</h5>
                    <ol>
                        @foreach($items as $item)
                                <li>{{ $item->name }} {{ $item->time}} val.</li>
                        @endforeach
                    </ol>

                    <ul>
{{--                        <li>Analizė – {{ $analise / 60 }} val.</li>--}}
                        @foreach($analise_items as $item)
                            <li>{{ $item->name }} {{ $item->time}} val.</li>
                        @endforeach
                        <li>Programavimas – {{ $items->total}} val.</li>
                        @foreach($test_items as $item)
                            <li>{{ $item->name }} {{ $item->time}} val.</li>
                        @endforeach
{{--                        <li>Testavimas, testavimo ataskaitos užpildymas – {{ $tests / 60 }} val.</li>--}}
                    </ul>

                    <p><span class="bold">Viso:</span> {{ $total}} val.</p>
                </div>
            </div>

        </div>

    </div>
@endsection
