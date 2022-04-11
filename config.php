<?php
return [
    //Get the configuration details ie DB connections
    'mysqldatabase' => [
        'name' => 'prs',
        'username' => 'root',
        'password' => '',
        'connection' => 'mysql:host=localhost',
        'options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]
    ],

    'sqlite' => [
        'path' => __DIR__.'/src/Core/Database/DB/prs.sqlite'
    ]
];
