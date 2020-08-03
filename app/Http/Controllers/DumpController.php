<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DumpController extends Controller
{
    protected function run()
    {
        $cmd = sprintf("mysqldump -u %s -p%s %s -R --triggers --events > %s",
            env('DB_USERNAME'),
            env('DB_PASSWORD'),
            env('DB_DATABASE'),
            env('DB_DUMP_PATH')
        );
        $result = shell_exec($cmd);

        $result = $cmd . PHP_EOL . json_encode($result, JSON_PRETTY_PRINT);

        return view('dump', compact('result'));
    }
}
