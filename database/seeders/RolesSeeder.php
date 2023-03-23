<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Roles;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name' => 'Super Administrator',
                'description' => 'For Development'
            ],
            [
                'name' => 'Administrator',
                'description' => 'For Admin'
            ]
        ];

        foreach ($data as $item) {
            Roles::create([
                'id' => Str::slug($item['name'], '.'),
                'name' => $item['name'],
                'description' => $item['description']
            ]);
        }
    }
}
