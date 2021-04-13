<div class="col-md-5">

    <div class="row">

        <div class="col-md-12">

            <div class="card">
                <div class="card-header">{{ __('Groups') }}</div>
                <div class="card-body">
                    <groups></groups>
                </div>
            </div>

            <div class="card">
                <div class="card-header">{{ __('Tasks') }}</div>
                <div class="card-body">
                    <tasks></tasks>
                </div>
            </div>

        </div>

        <div class="col-md-12">

            <div class="card">
                <div class="card-header">{{ __('Steps') }}</div>
                <div class="card-body">
                    <steps></steps>
                </div>
            </div>

        </div>


    </div>

    <div class="row">

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('All evaluations') }}</div>
                <div class="card-body">
                    <eval-items></eval-items>
                </div>
            </div>
        </div>

    </div>

</div>

<div class="col-md-7">
    <evaluations></evaluations>
    {{--    <div class="card">--}}
    {{--        <div class="card-header">{{ __('Evaluation') }}</div>--}}
    {{--        <div class="card-body">--}}

    {{--        </div>--}}
    {{--    </div>--}}
</div>

<div class="col-md-5">
    <users></users>
</div>



