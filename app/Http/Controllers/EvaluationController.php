<?php

namespace App\Http\Controllers;

use App\Evaluation;
use App\Item;
use App\Revision;
use App\Version;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\PhpWord;

class EvaluationController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $eval = Evaluation::create($request->except(['options', 'items']));
        return response()->json($eval->id);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Evaluation $evaluation
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Evaluation $evaluation)
    {
        list($analise, $analise_items, $test, $items, $total, $test_items) = $this->getParts($evaluation);

        return view('items.show', [
            'eval' => $evaluation,
            'analise' => $analise,
            'tests' => $test,
            'items' => $items,
            'total' => $total,
            'test_items' => $test_items,
            'analise_items' => $analise_items
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Evaluation $evaluation
     * @return \Illuminate\Http\Response
     */
    public function edit(Evaluation $evaluation)
    {
        return $evaluation;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Evaluation $evaluation
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $eval = Evaluation::findOrFail($id);

        $eval->fill($request->except(['id', 'options', 'items']));

        $eval->version = (int)$eval->last_version->version + 1;
        $eval->save();

        if (!$eval->isRevisionExist($eval->version)) {
            Version::make($eval, false);
        }

        return response()->json([
            'status' => 'success',
            'version' => $eval->version,
            'versions' => $this->versions($id),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Evaluation $evaluation
     * @return void
     * @throws \Exception
     */
    public function destroy(Evaluation $evaluation)
    {
        $evaluation->delete();

        return response()->json('Evaluation deleted');
    }

    public function get(Request $request)
    {
        $page = (int)$request->get('page');
        $take = 10;
        $skip = ($page - 1) * $take;

        $count = Evaluation::with(['items'])->count();
        $evaluations = Evaluation::with(['items'])->orderBy('created_at', 'desc')->skip($skip)->take($take)->get();

        return [
            'evaluations' => $evaluations,
            'records' => $count,
        ];
    }

    public function getOne(Request $request)
    {
        $id = $request->get('id');
        $eval = Evaluation::where('id', $id)->with(['items', 'options'])->first();

        return [
            'eval' => $eval,
            'versions' => $this->versions($id),
        ];
    }

    public function versions($id)
    {
        return DB::table('revisions')
            ->groupBy('version', 'evaluation_id')
            ->selectRaw('version, sum(time) as sum, evaluation_id')
            ->where('evaluation_id', $id)
            ->get();
    }

    public function export($id)
    {
        $e = Evaluation::findOrFail($id);

        if ($e) {

            $path = $this->saveDoc($e);

            if (is_file($path)) {
                return $e->name . '.docx';
            } else {
                throw new \Exception('Error saving Doc file', 500);
            }
        } else {
            throw new \Exception('Evaluation not found', 404);
        }
    }

    /**
     * @param Evaluation $evaluation
     * @return array
     */
    protected function getParts(Evaluation $evaluation): array
    {

        $analise_items = $evaluation->items()->where('group_id', 1)->get();
        $test_items = $evaluation->items()->where('group_id', 4)->get();
        $items = $evaluation->items()->where([['group_id', '<>', 1], ['group_id', '<>', 4]])->get();

        $analise = $analise_items->sum('time');
        $test = $test_items->sum('time');
        $items->total = $items->sum('time');

        $total = $analise + $test + $items->total;
        return array($analise, $analise_items, $test, $items, $total, $test_items);
    }

    /**
     * @param $e
     * @return string
     * @throws Exception
     */
    protected function saveDoc(Evaluation $e): string
    {
        list($analise, $analise_items, $test, $items, $total, $test_items) = $this->getParts($e);
        $options = $e->options;

        $font = env('WORD_FONT', 'Tahoma');

        $phpWord = new PhpWord();

        $multilevelNumberingStyleName = 'multilevel';

        $phpWord->addNumberingStyle(
            $multilevelNumberingStyleName,
            array(
                'type' => 'multilevel',
                'levels' => array(
                    array('format' => 'decimal', 'text' => '%1.', 'left' => 360, 'hanging' => 360, 'tabPos' => 360),
                    array('format' => 'upperLetter', 'text' => '%2.', 'left' => 720, 'hanging' => 360, 'tabPos' => 720),
                ),
            )
        );

        $predefinedMultilevel = array('color' => 'FF0000', 'listType' => \PhpOffice\PhpWord\Style\ListItem::TYPE_SQUARE_FILLED);

        $section = $phpWord->addSection();

        $section->addText('FUTUREVOICE PAKLAUSIMAS', ['name' => $font, 'size' => 10, 5], ['align' => 'center']);
        $section->addText('KREIPINIO ID: ' . $e->name, ['name' => $font, 'size' => 10, 5], ['align' => 'center']);
        $section->addTextBreak(1);
        $section->addText('Paklausimo data: ' . $e->date, ['name' => $font, 'size' => 10, 5]);
        $section->addText('Klientas: ' . $e->client, ['name' => $font, 'size' => 10, 5]);
        $section->addTextBreak(1);
        $section->addText('Paklausimas: ', array('name' => $font, 'size' => 10, 5, 'bold' => true));

        foreach ($options as $option) {
            $section->addListItem($option->name, 0, ['name' => $font, 'size' => 10, 5], $multilevelNumberingStyleName);
        }

        $section->addTextBreak(1);
        $section->addText('Atsakymas: ', array('name' => $font, 'size' => 10, 5, 'bold' => true));

        foreach ($items as $item) {
            $text = $item->name . ' - ' . ($item->time) . ' val.';
            $section->addListItem($text, 0, ['name' => $font, 'size' => 10, 5]);
        }

        $section->addTextBreak(1);

        foreach ($analise_items as $item) {
            $section->addListItem($item->name . ' - ' . ($item->time) . ' val.', 0, ['name' => $font, 'size' => 10, 5], $predefinedMultilevel);
        }

        $section->addListItem('Programavimas – ' . ($items->total) . ' val.', 0, ['name' => $font, 'size' => 10, 5], $predefinedMultilevel);

        foreach ($test_items as $item) {
            $section->addListItem($item->name . ' - ' . ($item->time) . ' val.', 0, ['name' => $font, 'size' => 10, 5], $predefinedMultilevel);
        }

        $section->addTextBreak(1);

        $section->addText('Suma: ' . ($total) . ' val.', ['name' => $font, 'size' => 10, 5]);

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');

        $path = public_path() . '/reports/' . $e->name . '.docx';
        $objWriter->save($path);
        return $path;
    }

    public function clone(Request $request)
    {
        $eval = $this->getOne($request);

        $saved_eval = $eval->replicate();
        $saved_eval->push();
        $saved_eval->items = [];
        $saved_eval->save();

        foreach ($eval->items as $item) {
            Item::create([
                'name' => $item->name,
                'time' => $item->time,
                'evaluation_id' => $saved_eval->id,
                'step_id' => $item->step_id,
                'group_id' => $item->group_id,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
    }

    public function revision(int $id = 0)
    {
        return Version::make(Evaluation::findOrFail($id));
    }

    public function restore($id, $version)
    {
        $eval = Evaluation::findOrFail($id);
        $eval->version = $version;
        $eval->save();

        if (!$eval->isRevisionExist($version)) {
            Version::make($eval, false);
        }

        return Version::restore(Evaluation::findOrFail($id), $version);
    }
}
