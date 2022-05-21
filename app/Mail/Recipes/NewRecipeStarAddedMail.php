<?php

namespace App\Mail\Recipes;

use App\Models\Recipe;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewRecipeStarAddedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    private Recipe $recipe;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Recipe $recipe)
    {
        $this->recipe = $recipe;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mails.recipes.newstar', [
            "recipe_name" => $this->recipe->name,
            "date" => $this->recipe->available_at->format("d/m/Y")
        ]);
    }
}
