<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StepSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $steps = [
            ['name' => 'Analizė', 'group_id' => 1],
            ['name' => 'Aplinkos kūrimas', 'group_id' => 1],
            ['name' => 'Aplinkos sukūrimas', 'group_id' => 1],
            ['name' => 'Pop-Up lango kūrimas', 'group_id' => 2],
            ['name' => 'Kontrolierio kūrimas', 'group_id' => 2],
            ['name' => 'Kontrolerio redagavimas', 'group_id' => 3],
            ['name' => 'JavaScript`o failo kūrimas', 'group_id' => 2],
            ['name' => 'JavaScript`o failo redagavimas', 'group_id' => 3],
            ['name' => 'Modelio kūrimas', 'group_id' => 2],
            ['name' => 'Modelio redagavimas', 'group_id' => 3],
            ['name' => 'Testavimas', 'group_id' => 4],
            ['name' => 'Testavimo ataskaitos kūrimas', 'group_id' => 4],
            ['name' => 'Testas', 'group_id' => 4],
        ];
        DB::table('steps')->insert($steps);
    }
}
