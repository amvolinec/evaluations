<?php


namespace App\Revisions;


use App\Evaluation;
use App\Events\NewEvaluation;
use App\Item;
use App\Observers\EvaluationObserver;
use App\Revision;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class MakeRevision
{

    private Evaluation $evaluation;

    public function make(Evaluation $evaluation, $new = true)
    {

        $this->evaluation = $evaluation;

        if ($new) {
            $this->evaluation->version = $this->evaluation->last_version->version + 1;
            $this->evaluation->save();
        }

        foreach ($this->evaluation->items as $item) {
            Revision::create([
                'version' => $this->evaluation->version,
                'name' => $item->name,
                'description' => $item->description,
                'time' => $item->time,
                'evaluation_id' => $this->evaluation->id,
                'step_id' => $item->step_id,
                'group_id' => $item->group_id,
                'user_id' => Auth::id()
            ]);
        }

        return ['status' => 'success', 'version' => $this->evaluation->version];
    }

    public function restore(Evaluation $eval, $version)
    {
        $items = Revision::select('id', 'name', 'time', 'evaluation_id', 'step_id', 'group_id')
            ->where([['evaluation_id', $eval->id], ['version', $version]])
            ->get();

        $eval->items()->delete();

        $observer = new EvaluationObserver();
        $observer->fetchItems($items, $eval);

        event(new NewEvaluation());

        return $eval->items;
    }
}
