<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateEnabledDevicesViewCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sqlview:CreateEnabledDevicesView';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create SQL view with devices enabled on the network.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        DB::statement('
            CREATE VIEW IF NOT EXISTS
                    enabled_devices
                AS
                SELECT
                    devices.mac,
                    devices.name,
                    categories.vlan,
                    devices.valid_from,
                    devices.valid_to
                FROM
                    devices
                INNER JOIN
                    categories ON devices.category_id=categories.id
                WHERE
                    devices.enabled=1;
        ');
    }
}
