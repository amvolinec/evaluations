<?php

namespace App\Observers;

use App\Evaluation;
use App\Events\NewEvaluation;
use App\Item;
use App\Option;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EvaluationObserver
{
    /**
     * Handle the evaluation "created" event.
     *
     * @param \App\Evaluation $evaluation
     * @param Request $request
     * @return void
     */
    public function created(Evaluation $evaluation)
    {
        $request = request()->all();

        $this->fetchOptions($request['options'], $evaluation);
        $this->fetchItems($request['items'], $evaluation);

        event(new NewEvaluation());
    }

    /**
     * Handle the evaluation "updated" event.
     *
     * @param \App\Evaluation $evaluation
     * @return void
     */
    public function updated(Evaluation $evaluation)
    {
        $request = request()->all();

        Item::where('evaluation_id', $evaluation->id)->delete();
        Option::where('evaluation_id', $evaluation->id)->delete();

        $this->fetchOptions($request['options'], $evaluation);
        $this->fetchItems($request['items'], $evaluation);

        event(new NewEvaluation());
    }

    /**
     * Handle the evaluation "deleted" event.
     *
     * @param \App\Evaluation $evaluation
     * @return void
     */
    public function deleted(Evaluation $evaluation)
    {
        event(new NewEvaluation());
    }

    /**
     * Handle the evaluation "restored" event.
     *
     * @param \App\Evaluation $evaluation
     * @return void
     */
    public function restored(Evaluation $evaluation)
    {
        //
    }

    /**
     * Handle the evaluation "force deleted" event.
     *
     * @param \App\Evaluation $evaluation
     * @return void
     */
    public function forceDeleted(Evaluation $evaluation)
    {
        //
    }

    protected function fetchOptions($options, Evaluation $eval)
    {
        foreach ($options as $option) {
            if (!empty($option['name'])) {
                $new_option = new Option();
                $new_option->name = $option['name'];
                $new_option->evaluation()->associate($eval)->save();
            }
        }
    }

    protected function fetchItems($items, Evaluation $eval)
    {
        foreach ($items as $item) {
            $new_item = new Item();
            $new_item->name = $item['name'];
            $new_item->time = (int)$item['time'];
            $new_item->step_id = (int)$item['step_id'];
            $new_item->group_id = (int)$item['group_id'];
            $new_item->evaluation()->associate($eval)->save();
        }
    }
}
