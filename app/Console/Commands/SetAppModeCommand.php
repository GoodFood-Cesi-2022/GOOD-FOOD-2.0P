<?php

namespace App\Console\Commands;

use App\Enums\AppModes;
use Illuminate\Console\Command;

class SetAppModeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:mode {mode}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set the mode of the application, normal or configuration';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $mode = AppModes::tryFrom($this->argument('mode'));

        if(!$mode) {
            $this->error("Mode is not allowed");
        }

        set_app_mode($mode->value);

        $this->info("App is now running in mode {$mode->value}");

        return 0;
    }
}
