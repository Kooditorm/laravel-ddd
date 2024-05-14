<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Domain directory name
    |--------------------------------------------------------------------------
    |
    | Here you can configure the domain name directory name for the application.
    | Provide personalized directory name customization for users. Default name domain
    |
    |
    */
    'domain_name' => env('DOMAIN_NAME', 'Domains'),
    /*
    |--------------------------------------------------------------------------
    | System interface directory name
    |--------------------------------------------------------------------------
    |
    | Here you can configure the domain name directory name for the application.
    | Provide personalized directory name customization for users.
    | This directory includes the program script directory and the controller directory.
    | Default name interface
    |
    |
    */
    'interface_name' => env('INTERFACE_NAME', 'Interfaces'),

    /*
    | --------------------------------------------------------------------------
    | The event handler mappings for the application.
    | --------------------------------------------------------------------------
    |
    | Here you can configure the event handler mapping for the application.
    | Facilitate user management of event mapping relationships.
    | The configuration here can be used together with the event registration of the original framework
    |
    |
    */
    'listen' => [

    ],

];
