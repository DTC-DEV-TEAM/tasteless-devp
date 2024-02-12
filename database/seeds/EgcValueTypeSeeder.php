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
            'name' => '₱ 500',
            ],
            [
                'name' => '₱ 500',
                'value' => '500',
                'status' => 'ACTIVE',
                'created_by' => '3',
                'created_at' => date('Y-m-d H:i:s')
            ]);

        DB::table('egc_value_types')->updateOrInsert([
            'name' => '₱ 1000',
            ],
            [
                'name' => '₱ 1000',
                'value' => '1000',
                'status' => 'ACTIVE',
                'created_by' => '3',
                'created_at' => date('Y-m-d H:i:s')
            ]);

        DB::table('egc_value_types')->updateOrInsert([
            'name' => '₱ 5000',
            ],
            [
                'name' => '₱ 5000',
                'value' => '5000',
                'status' => 'ACTIVE',
                'created_by' => '3',
                'created_at' => date('Y-m-d H:i:s')
            ]);
    }
}
