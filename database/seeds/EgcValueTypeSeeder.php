<?php

use Illuminate\Database\Seeder;

class EgcValueTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('egc_value_types')->updateOrInsert([
            'name' => 'P1000',
            ],
            [
                'name' => 'P1000',
                'value' => '1000',
                'status' => 'ACTIVE',
                'created_by' => '7',
                'created_at' => date('Y-m-d H:i:s')
            ]);

        DB::table('egc_value_types')->updateOrInsert([
            'name' => 'P5000',
            ],
            [
                'name' => 'P5000',
                'value' => '5000',
                'status' => 'ACTIVE',
                'created_by' => '7',
                'created_at' => date('Y-m-d H:i:s')
            ]);
    }
}
