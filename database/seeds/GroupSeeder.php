<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $groups = [
            ['name' => 'Analizė'],
            ['name' => 'Funkcionalo papildymas'],
            ['name' => 'Pataisymai'],
            ['name' => 'Testai'],
        ];
        DB::table('groups')->insert($groups);
    }
}
