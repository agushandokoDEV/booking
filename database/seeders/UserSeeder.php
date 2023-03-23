<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid as GeneratorUuid;
use App\Models\User;
use App\Models\Roles;

class UserSeeder extends Seeder
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
                'email' => 'super.admin@laravel.com',
                'password' => 'admin',
                'role_id' => Roles::where('id', 'super.administrator')->first()->id
            ],
            [
                'name' => 'Administrator',
                'email' => 'admin@laravel.com',
                'password' => 'admin',
                'role_id' => Roles::where('id', 'administrator')->first()->id
            ]
        ];

        foreach ($data as $item) {
            User::create([
                'id'=>GeneratorUuid::uuid4()->toString(),
                'name' => $item['name'],
                'username' => Str::slug($item['name'], '.'),
                'email' => $item['email'],
                'password' => $item['password'],
                'role_id' => $item['role_id']
            ]);
        }
    }
}
