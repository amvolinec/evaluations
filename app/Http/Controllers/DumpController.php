<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DumpController extends Controller
{
    protected function run()
    {
        if(!empty(env('DB_PASSWORD'))){
            $cmd = sprintf("mysqldump -u %s -p%s -R --skip-triggers --no-create-info --no-create-db %s > %s",
                env('DB_USERNAME'),
                env('DB_PASSWORD'),
                env('DB_DATABASE'),
                env('DB_DUMP_PATH')
            );
        } else {
            $cmd = sprintf("mysqldump -u %s -R --skip-triggers --no-create-info --no-create-db %s > %s \n",
                env('DB_USERNAME'),
                env('DB_DATABASE'),
                env('DB_DUMP_PATH')
            );
        }

        shell_exec($cmd);

        $result = $cmd;

        return view('dump', compact('result'));
    }
}
