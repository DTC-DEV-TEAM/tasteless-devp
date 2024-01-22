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
            'name' => 'Digital Walker',
            ],
            [
                'name' => 'Digital Walker',
                'concept' => 'DW',
                'status' => 'ACTIVE',
                'created_by' => 7,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        DB::table('store_logos')->updateOrInsert([
            'name' => 'Beyond the Box',
            ],
            [
                'name' => 'Beyond the Box',
                'concept' => 'BTB',
                'status' => 'ACTIVE',
                'created_by' => 7,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        DB::table('store_logos')->updateOrInsert([
            'name' => 'Digital Walker and Beyond the Box',
            ],
            [
                'name' => 'Digital Walker and Beyond the Box',
                'concept' => 'DWxBTB',
                'status' => 'ACTIVE',
                'created_by' => 7,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        DB::table('store_logos')->updateOrInsert([
            'name' => 'Open Source',
            ],
            [
                'name' => 'Open Source',
                'concept' => 'OS',
                'status' => 'ACTIVE',
                'created_by' => 7,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        DB::table('store_logos')->updateOrInsert([
            'name' => 'Store',
            ],
            [
                'name' => 'Store',
                'concept' => 'ST',
                'status' => 'ACTIVE',
                'created_by' => 7,
                'created_at' => date('Y-m-d H:i:s')
            ]);
    }
}
