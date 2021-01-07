<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class setup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup the application with a new database and new set of data';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->warn('Running fresh migrations and seeding...');

        Artisan::call('migrate:fresh --seed');

        $this->warn('Setting up passport...');

        Artisan::call('passport:install');

        $this->warn('Linking storage...');

        Artisan::call('storage:link');

        $this->info('All done!');
    }
}
