<?php

namespace Dohnall\LaravelEav\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Vtech\Eav\Models\Attribute;
use Vtech\Eav\Models\EntityType;

class EavTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eav:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'EAV test';

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
        $this->info('Running...');
    }
}
