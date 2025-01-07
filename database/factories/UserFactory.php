<?php

namespace Database\Factories;

use App\Entities\User;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;

class UserFactory
{

    public static function create(array $attributes = []): User
    {
        $faker = Faker::create();

        $user = new User();

        $name = $attributes['name'] ?? $faker->name;
        $user->setName($name);

        $email = $attributes['email'] ?? $faker->unique()->safeEmail;
        $user->setEmail($email);

        $password = $attributes['password'] ?? 'testPassword';
        $user->setPassword(Hash::make($password));

        return $user;
    }
}
