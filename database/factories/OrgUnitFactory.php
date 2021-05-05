<?php

namespace Database\Factories;

use App\Models\OrgUnit;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class OrgUnitFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OrgUnit::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'orgUnitCode' => "OU-" . $this->faker->numberBetween(1, 100),
            'hasDictionaries' => false,
            'parent_id' => OrgUnit::where('hasDictionaries', true)->first()->id,
        ];
    }
}
