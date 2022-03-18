<?php

namespace Database\Factories;

use App\Models\OrgUnit;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrgUnitFactory extends Factory
{
    const DEFAULT_PREFIX = 'OU-';

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
    public function definition(): array
    {
        return [
            'org_unit_code' => self::DEFAULT_PREFIX . $this->faker->numberBetween(1, 100),
            'has_dictionaries' => false,
            'parent_id' => OrgUnit::where(['has_dictionaries' => true])->first()->id,
        ];
    }
}
