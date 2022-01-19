<?php

namespace Database\Factories;

use App\Models\Region;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Http\Traits\HelperTrait;
class RegionFactory extends Factory
{
    use HelperTrait;
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Region::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $codes = array_keys($this->getStates());
        $rand = array_rand($codes);

        return [
            //
            'name' => $this->faker->name(),
            'state'=> $this->getStates()[$codes[$rand]]
        ];
    }
}
