<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(EgcValueTypeSeeder::class);
        $this->call(StoreBrandTypeSeeder::class);
        $this->call(StoreStatusSeeder::class);
        $this->call(StoreLogosSeeder::class);
        $this->call(QrTypeSeeder::class);
    }
}
