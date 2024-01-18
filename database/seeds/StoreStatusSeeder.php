<?php

use Illuminate\Database\Seeder;

class StoreStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('store_statuses')->updateOrInsert([
            'name' => 'Pending Customer',
            ],
            [
                'name' => 'Pending Customer',
                'status' => 'ACTIVE',
                'created_by' => 7,
                'created_at' => date('Y-m-d H:i:s')
            ]);

        DB::table('store_statuses')->updateOrInsert([
            'name' => 'Pending Cashier',
            ],
            [
                'name' => 'Pending Cashier',
                'status' => 'ACTIVE',
                'created_by' => 7,
                'created_at' => date('Y-m-d H:i:s')
            ]);

        DB::table('store_statuses')->updateOrInsert([
            'name' => 'OIC Approval',
            ],
            [
                'name' => 'OIC Approval',
                'status' => 'ACTIVE',
                'created_by' => 7,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        
        DB::table('store_statuses')->updateOrInsert([
            'name' => 'Email Sent',
            ],
            [
                'name' => 'Email Sent',
                'status' => 'ACTIVE',
                'created_by' => 7,
                'created_at' => date('Y-m-d H:i:s')
            ]);

        DB::table('store_statuses')->updateOrInsert([
            'name' => 'Approved',
            ],
            [
                'name' => 'Approved',
                'status' => 'ACTIVE',
                'created_by' => 7,
                'created_at' => date('Y-m-d H:i:s')
            ]);

        DB::table('store_statuses')->updateOrInsert([
            'name' => 'Email failed',
            ],
            [
                'name' => 'Email Failed',
                'status' => 'ACTIVE',
                'created_by' => 7,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        DB::table('store_statuses')->updateOrInsert([
            'name' => 'Voided',
            ],
            [
                'name' => 'Voided',
                'status' => 'ACTIVE',
                'created_by' => 7,
                'created_at' => date('Y-m-d H:i:s')
            ]);
    }
}
