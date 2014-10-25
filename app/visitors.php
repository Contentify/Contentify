<?php

/*
|--------------------------------------------------------------------------
| Updates the global visitor statistics.
|--------------------------------------------------------------------------
*/

if (! App::runningInConsole() and installed()) {
    $today          = time();
    $isNewVisitor   = (Session::get('ipLogged') == null);

    if (Session::get('ipLogged') and (Session::get('ipLogged') != date('d', $today))) {
        $isNewVisitor = true; // Change of day makes every user a new visitor
    }

    if ($isNewVisitor) {   
        $ip = getenv('REMOTE_ADDR'); // Get the client agent's IP

        $rowsAffected = DB::table('visits')->whereIp($ip)->whereVisitedAt(date('Y-m-d', $today))
                            ->increment('user_agents');

        if (! $rowsAffected) {
            DB::table('visits')->insert(array('ip' => $ip, 'user_agents' => 1, 'visited_at' => date('Y-m-d', $today)));
        }
        
        Session::put('ipLogged', date('d', $today)); // Keep in our session-mind the day we logged this IP
    }
}