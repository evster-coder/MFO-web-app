<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\OrgUnit;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    const DEFAULT_PASSWORD = 'testuser';

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
    public function definition(): array
    {
        return [
            'full_name' => $this->faker->name(),
            'org_unit_id' => OrgUnit::where(['has_dictionaries' => false])->first()->id,
            'blocked' => false,
            'need_change_password' => false,
            'username' => $this->faker->name(),
            'password' => Hash::make(self::DEFAULT_PASSWORD),
            'remember_token' => Str::random(10),
        ];
    }
}
