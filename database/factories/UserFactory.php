<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\OrgUnit;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'FIO' => $this->faker->name(),
            'orgunit_id' => OrgUnit::where('hasDictionaries', false)->first()->id,
            'blocked' => false,
            'needChangePassword' => false,
            'username' => $this->faker->name(),
            'password' => Hash::make('testuser'),
            'remember_token' => Str::random(10),
        ];
    }
}
