<?php


namespace App\Revisions;


use App\Evaluation;
use App\Revision;
use Illuminate\Support\Facades\Auth;

class MakeRevision
{

    private Evaluation $evaluation;

    public function make(Evaluation $evaluation) {

        $this->evaluation = $evaluation;

        foreach ($this->evaluation->items as $item){
            Revision::create([
                'version' => $this->evaluation->version,
                'name' => $item->name,
                'description' => $item->description,
                'time' => $item->time,
                'evaluation_id' => $this->evaluation->id,
                'group_id' => $item->group_id,
                'user_id' => Auth::id()
            ]);
        }

        $this->evaluation->version ++;
        $this->evaluation->save();

        return ['status' => 'success'];
    }
}
