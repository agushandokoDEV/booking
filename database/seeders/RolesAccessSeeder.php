<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid as GeneratorUuid;
use App\Models\Menu;
use App\Models\RolesAccess;

class RolesAccessSeeder extends Seeder
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
                'role_id' => 'super.administrator',
                'menu_id' => Menu::where('code','dasboard-read')->first()->id,
                'menu_code' => 'dasboard-read',
                'allowed' => true
            ],
            [
                'role_id' => 'administrator',
                'menu_id' => Menu::where('code', 'dasboard-read')->first()->id,
                'menu_code' => 'dasboard-read',
                'allowed' => true
            ]
        ];

        foreach ($data as $item) {
            RolesAccess::create([
                'id' => GeneratorUuid::uuid4()->toString(),
                'role_id' => $item['role_id'],
                'menu_id' => $item['menu_id'],
                'menu_code' => $item['menu_code'],
                'allowed' => $item['allowed'],
            ]);
        }
    }
}
