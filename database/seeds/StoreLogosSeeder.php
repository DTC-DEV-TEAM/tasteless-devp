<?php

use Illuminate\Database\Seeder;

class StoreLogosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('store_logos')->updateOrInsert([
            'name' => 'Tasteless (Pink)',
            ],
            [
                'name' => 'Tasteless (Pink)',
                'concept' => 'Tasteless',
                'status' => 'ACTIVE',
                'created_by' => 7,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        DB::table('store_logos')->updateOrInsert([
            'name' => 'Tasteless (Blue)',
            ],
            [
                'name' => 'Tasteless (Blue)',
                'concept' => 'Tasteless',
                'status' => 'ACTIVE',
                'created_by' => 7,
                'created_at' => date('Y-m-d H:i:s')
            ]);
    }
}
