<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DumpController extends Controller
{
    protected function run()
    {
        if(env('DB_DUMP_SUDO') === true){
//            shell_exec('sudo su');

            $cmd = sprintf('sudo mysqldump -R --skip-triggers --no-create-info --no-create-db --ignore-table=%2$s.migrations %1$s > %2$s',
                env('DB_DATABASE'),
                env('DB_DUMP_PATH') . '/' .env('DB_DATABASE'). '_' . date('Ymd_His') . '.sql'
            );

        } else {
            if(!empty(env('DB_PASSWORD'))){
                $cmd = sprintf('mysqldump -u %1$s -p%2$s -R --skip-triggers --no-create-info --no-create-db --ignore-table=%3$s.migrations %3$s > %4$s',
                    env('DB_USERNAME'),
                    env('DB_PASSWORD'),
                    env('DB_DATABASE'),
                    env('DB_DUMP_PATH') . '/' .env('DB_DATABASE'). '_' . date('Ymd_His') . '.sql'
                );
            } else {
                $cmd = sprintf('mysqldump -u %1$s -R --skip-triggers --no-create-info --no-create-db --ignore-table=%2$s.migrations %2$s > %3$s',
                    env('DB_USERNAME'),
                    env('DB_DATABASE'),
                    env('DB_DUMP_PATH') . '/' .env('DB_DATABASE'). '_' . date('Ymd_His') . '.sql'
                );
            }
        }

        shell_exec($cmd);

        $result = $cmd;

        return view('dump', compact('result'));
    }
}
