<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceDeskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('service_desks')->insert([
            ['name' => 'PULSE',        'sys_id' => '638d4e221bd8f550918365bfe54bcb3f'],
            ['name' => 'MVOLA',        'sys_id' => 'a38d4e221bd8f550918365bfe54bcb3b'],
            ['name' => 'STELLARIX-MG', 'sys_id' => '238d4e221bd8f550918365bfe54bcb43'],
            ['name' => 'TELCO',        'sys_id' => '174bc7a11b1d9610918365bfe54bcb12'],
            ['name' => 'TELMA',        'sys_id' => 'eb8d4e221bd8f550918365bfe54bcb44'],
            ['name' => 'TOGOCOM',      'sys_id' => 'ef8d4e221bd8f550918365bfe54bcb46'],
        ]);
    }
}
