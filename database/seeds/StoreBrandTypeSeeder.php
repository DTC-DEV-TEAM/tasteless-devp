<?php

use Illuminate\Database\Seeder;

class StoreBrandTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('store_brand_types')->updateOrInsert([
            'name' => 'Beyond the Box',
            ],
            [
                'name' => 'Beyond the Box',
                'status' => 'ACTIVE'
            ]);

        DB::table('store_brand_types')->updateOrInsert([
            'name' => 'BTB x open_source',
            ],
            [
                'name' => 'BTB x open_source',
                'status' => 'ACTIVE'
            ]);

        DB::table('store_brand_types')->updateOrInsert([
            'name' => 'Digital Walker',
            ],
            [
                'name' => 'Digital Walker',
                'status' => 'ACTIVE'
            ]);
            
        DB::table('store_brand_types')->updateOrInsert([
            'name' => 'open_source',
            ],
            [
                'name' => 'open_source',
                'status' => 'ACTIVE'
            ]);
    }
}
