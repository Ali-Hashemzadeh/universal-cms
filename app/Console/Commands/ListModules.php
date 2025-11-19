<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Modules\Module;

class ListModules extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'modules:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all registered modules';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $modules = $this->getLaravel()->make('modules');

        if ($modules->isEmpty()) {
            $this->info('No modules are currently loaded.');
            return;
        }

        $headers = ['Name', 'Description', 'Version'];
        $data = $modules->map(function (Module $module) {
            return [
                'Name' => $module->getName(),
                'Description' => $module->getDescription(),
                'Version' => $module->getVersion(),
            ];
        });

        $this->table($headers, $data);
    }
}
