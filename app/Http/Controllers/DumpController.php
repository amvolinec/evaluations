<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DumpController extends Controller
{
    protected function run()
    {
        $result = "Dump successfully created!";

        if(env('DB_DUMP_SUDO') === true){

            shell_exec('/usr/local/bin/dumper.sh');

        } else {
            if(!empty(env('DB_PASSWORD'))){
                $cmd = sprintf('mysqldump -u %1$s -p%2$s -R --skip-triggers --no-create-info --no-create-db --ignore-table=%3$s.migrations --databases %3$s %5$s | gzip > %4$s.gz',
                    env('DB_USERNAME'),
                    env('DB_PASSWORD'),
                    env('DB_DATABASE'),
                    env('DB_DUMP_PATH') . '/' .env('DB_DATABASE'). '_' . date('Ymd_His') . '.sql',
                    env('DB_FORMS_DATABASE')
                );
            } else {
                $cmd = sprintf('mysqldump -u %1$s -R --skip-triggers --no-create-info --no-create-db --ignore-table=%2$s.migrations --databases %2$s %4$s | gzip > %3$s.gz',
                    env('DB_USERNAME'),
                    env('DB_DATABASE'),
                    env('DB_DUMP_PATH') . '/' .env('DB_DATABASE'). '_' . date('Ymd_His') . '.sql',
                    env('DB_FORMS_DATABASE')
                );
            }

            shell_exec($cmd);
            $result = $cmd;
        }

        return view('dump', compact('result'));
    }
}
