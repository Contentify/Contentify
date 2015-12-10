<?php namespace Contentify\Models;

use Contentify\Vendor\MySqlDump;
use Config, Job;

class BackupDatabaseJob extends Job {

    protected $timeSpan = 1440; // 60 minutes * 24 = 24h (once per day)

    public function run($executed)
    {
        if (! Config::get('app.dbBackup')) {
            return;
        }

        $dump = new MySqlDump();

        $con        = Config::get('database.connections.mysql');
        $dateTime   = date('M-d-Y_H-i');
        $filename   = storage_path().'/database/'.$dateTime.'.sql';

        $dump->host     = $con['host'];
        $dump->user     = $con['username'];
        $dump->pass     = $con['password'];
        $dump->db       = $con['database'];
        $dump->filename = $filename;
        $dump->start();
    }

}