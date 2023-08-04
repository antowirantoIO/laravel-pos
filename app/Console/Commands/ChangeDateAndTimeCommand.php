<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ChangeDateAndTimeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'change:date';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $date = date('Y-m-d H:i:s');
        exec('sudo date -s "' . $date . '"');

        // check if windows or linux
        if(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            exec('time ' . date('H:i:s'));
        } else {
            exec('sudo date -s "' . $date . '"');
        }

        return 0;
    }
}
