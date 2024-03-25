<?php

use Illuminate\Database\Seeder;

class QrTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('qr_types')->updateOrInsert([
            'name' => 'Gift Code',
            ],
            [
                'name' => 'Gift Code',
                'status' => 'ACTIVE',
                'created_by' => 7,
                'created_at' => date('Y-m-d H:i:s')
            ]);

        DB::table('qr_types')->updateOrInsert([
            'name' => 'QR Code',
            ],
            [
                'name' => 'QR Code',
                'status' => 'ACTIVE',
                'created_by' => 7,
                'created_at' => date('Y-m-d H:i:s')
            ]);
    }
}
