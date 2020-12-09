<?php

namespace Database\Factories;

use App\Models\Blog;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BlogFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Blog::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
       return [
            'title'           => $this->faker->text(100),
            'slug_url'        => Str::slug($this->faker->text(100), '-'),
            'description'     => $this->faker->text(500),
            'meta_title'      => $this->faker->text(100),
            'meta_description'=> $this->faker->text(200),
            'keyword'         => $this->faker->text(100),
            'status'          => $this->faker->numberBetween(0,1),
        ];
    }
}
