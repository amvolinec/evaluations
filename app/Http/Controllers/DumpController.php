<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DumpController extends Controller
{
    protected function run()
    {
        if(empty(env('DB_PASSWORD'))){
            $cmd = sprintf("mysqldump -u %s -p%s %s -R --triggers --events > %s",
                env('DB_USERNAME'),
                env('DB_PASSWORD'),
                env('DB_DATABASE'),
                env('DB_DUMP_PATH')
            );
        } else {
            $cmd = sprintf("mysqldump -u %s %s -R --triggers --events > %s",
                env('DB_USERNAME'),
                env('DB_DATABASE'),
                env('DB_DUMP_PATH')
            );
        }

        shell_exec($cmd);
        shell_exec("\n");

        $result = $cmd;

        return view('dump', compact('result'));
    }
}
