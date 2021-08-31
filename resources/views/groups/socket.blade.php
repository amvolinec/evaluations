<div class="col-md-2">

    <div class="row">

        <div class="col-md-12">

            <div class="card">
                <div class="card-header">1. {{ __('Section') }}</div>
                <div class="card-body">
                    <groups></groups>
                </div>
            </div>

            <div class="card">
                <div class="card-header">2. {{ __('Tasks') }}</div>
                <div class="card-body">
                    <tasks></tasks>
                </div>
            </div>

        </div>
    </div>

</div>

<div class="col-md-3">

    <div class="card">
        <div class="card-header">3. {{ __('Steps') }}</div>
        <div class="card-body">
            <steps></steps>
        </div>
    </div>
</div>

<div class="col-md-7">
    <evaluations></evaluations>
</div>

<div class="col-md-12">
    <div class="card">
        <div class="card-header">{{ __('All evaluations') }}</div>
        <div class="card-body">
            <eval-items></eval-items>
        </div>
    </div>
</div>

<div class="col-md-5">
    <users></users>
</div>



