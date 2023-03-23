<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid as GeneratorUuid;
use Illuminate\Support\Str;
use App\Models\Menu;

class MenuSeeder extends Seeder
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
                'title' => 'Dashboard',
                'code' => 'dasboard-read',
                'url' => '/dasboard',
                'description' => 'Dashboard',
                'is_menu' => true
            ]
        ];

        $sorting = 1;
        foreach ($data as $item) {
            Menu::create([
                'id' => GeneratorUuid::uuid4()->toString(),
                'title' => $item['title'],
                'code' => $item['code'],
                'url' => $item['url'],
                'sorting' => $sorting,
                'description' => $item['description'],
                'is_menu' => $item['is_menu']
            ]);
            $sorting++;
        }
    }
}
