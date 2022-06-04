<?php

namespace App\Console\Commands\Recipes;

use App\Models\Recipe;
use Illuminate\Console\Command;

class DeleteRecipesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recipes:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete all recipes marked to be trash';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        Recipe::toDelete()->delete();


        return 0;
    }
}
