<?php
return $next->header('Access-Control-Allow-Origin', '*')
    ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
    ->header('Access-Control-Allow-Headers', 'X-Auth-Token, X-Requested-With, Content-Type, Accept, Authorization');
