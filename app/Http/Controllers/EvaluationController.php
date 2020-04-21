<?php

namespace App\Http\Controllers;

use App\Evaluation;
use App\Events\NewEvaluation;
use App\Item;
use App\Option;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\PhpWord;

class EvaluationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Evaluation::create($request->except(['options', 'items']));

        return response()->json('Evaluation added');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Evaluation $evaluation
     * @return \Illuminate\Http\Response
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
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Evaluation $evaluation)
    {
        $evaluation->fill($request->except(['id', 'options', 'items']));
        ++$evaluation->version;
        $evaluation->save();

        return response()->json('Evaluation updated');
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

    public function get()
    {
        return Evaluation::with(['items'])->orderBy('name')->get();
    }

    public function getOne(Request $request)
    {
        return Evaluation::where('id', $request->get('id'))->with(['items', 'options'])->first();
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

        $section = $phpWord->addSection();

        $section->addText('FUTUREVOICE PAKLAUSIMAS', ['name' => 'Arial', 'size' => 10, 5], ['align' => 'center']);
        $section->addText('KREIPINIO ID: ' . $e->name, ['name' => 'Arial', 'size' => 10, 5], ['align' => 'center']);
        $section->addTextBreak(1);
        $section->addText('Paklausimo data: ' . $e->date, ['name' => 'Arial', 'size' => 10, 5]);
        $section->addText('Klientas: ' . $e->client, ['name' => 'Arial', 'size' => 10, 5]);
        $section->addTextBreak(1);
        $section->addText('Paklausimas: ', array('name' => 'Arial', 'size' => 10, 5, 'bold' => true));

        foreach ($options as $option) {
            $section->addListItem($option->name);
        }

        $section->addTextBreak(1);
        $section->addText('Atsakymas: ', array('name' => 'Arial', 'size' => 10, 5, 'bold' => true));

        foreach ($items as $item) {
            $text = $item->name . ' - ' . ($item->time / 60) . ' val.';
            $section->addListItem($text, 0, null, $multilevelNumberingStyleName);
        }

        $section->addTextBreak(1);

        foreach ($analise_items as $item) {
            $section->addListItem($item->name . ' - ' . ($item->time / 60) . ' val.');
        }

        $section->addListItem('Programavimas – ' . ($items->total / 60) . ' val.');

        foreach ($test_items as $item) {
            $section->addListItem($item->name . ' - ' . ($item->time / 60) . ' val.');
        }

        $section->addTextBreak(1);

        $section->addText('Viso: ' . ($total / 60) . ' val.', ['name' => 'Arial', 'size' => 10, 5]);

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');

        $path = public_path() . '/reports/' . $e->name . '.docx';
        $objWriter->save($path);
        return $path;
    }
}
