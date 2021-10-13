<?php
    $cleardb_url = parse_url(getenv("CLEARDB_DATABASE_URL"));

    define('URL','https://fablab-system.herokuapp.com/');

    define('HOST',$cleardb_url["host"]);
    define('DB',substr($cleardb_url["path"],1));
    define('USER',$cleardb_url["user"]);
    define('PASSWORD',$cleardb_url["pass"]);
    define('CHARSET','utf8mb4');

    $active_group = 'default';
    $query_builder = TRUE;


?>