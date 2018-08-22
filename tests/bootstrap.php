<?php

// turn on all errors
error_reporting(-1);

setlocale(LC_ALL, 'en_US.utf-8');

set_time_limit(-1);

clearstatcache();

if (extension_loaded('xdebug')) {
    ini_set('xdebug.cli_color', 1);
    ini_set('xdebug.collect_params', 0);
    ini_set('xdebug.dump_globals', 'on');
    ini_set('xdebug.show_local_vars', 'on');
    ini_set('xdebug.max_nesting_level', 100);
    ini_set('xdebug.var_display_max_depth', 4);
}

$vendorPath = dirname(dirname(__FILE__)) . '/vendor';

if (file_exists("{$vendorPath}/autoload.php")) {
    require "{$vendorPath}/autoload.php";
} else {
    fwrite(STDOUT, "The file {$vendorPath}/autoload.php doesn't exists. Stop..." . PHP_EOL);
    exit (1);
}
