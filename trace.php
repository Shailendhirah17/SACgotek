<?php
register_shutdown_function(function() {
    $files = get_included_files();
    echo "LAST INCLUDED FILES:\n";
    $last_files = array_slice($files, -10);
    foreach($last_files as $f) {
        echo $f . "\n";
    }
});
require 'artisan';
