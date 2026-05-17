<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Livestock;
use App\Models\Abort;
use App\Models\Milking;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $commentable = null;
        
        if ($this->faker->boolean(70)) {
            $commentables = [
                Abort::class,
                Milking::class,
            ];

            $commentableClass = $this->faker->randomElement($commentables);
            $commentable = $commentableClass::factory()->create();
        }

        return [
            'text' => $this->faker->paragraph(),
            'livestock_id' => Livestock::factory(),
            'commentable_id' => $commentable ? $commentable->id : null,
            'commentable_type' => $commentable ? $commentable->getMorphClass() : null,
        ];
    }
}
