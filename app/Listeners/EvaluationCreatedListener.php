<?php

namespace App\Listeners;

use App\Events\EvaluationCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class EvaluationCreatedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  EvaluationCreated  $event
     * @return void
     */
    public function handle(EvaluationCreated $event)
    {
        Log::info('Evaluation created ' . date('Y-m-d H:i:s') . $event->evaluation->id);
    }
}
