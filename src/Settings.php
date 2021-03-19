<?php
namespace LinksApplication;

/**
 * General settings
 */
const settings =
[
    'siteUrl'   => 'http://localhost/',      // For link generation
    'database'  =>                          // MySql settings
    [
        'hostname'  => 'p:localhost',
        'username'  => 'root',
        'password'  => 'root123',
        'dbname'    => 'links'
    ],
    'action'    =>                          // Actions
    [
        'register' =>                       // Register new user and/or get user ID if exists
        [
            'callback' => 'UserRegister',   // Action callback class
            'requiredParameters' =>         // We'll check request for this parameters
                ['login'],
            'parameters'            => []   // This parameters will be used only if request includes them
        ],
        'get' =>                            // Get small link by URL
        [
            'callback'              => 'GetLink',
            'requiredParameters'    => ['url', 'user'],
        ],
        'follow' =>                         // Redirect to origin by small link
        [
            'callback'              => 'FollowLink',
            'requiredParameters'    => ['link'],
        ],
        'links' =>                          // Get statistics
        [
            'callback'              => 'LinkStatistics',
            'parameters'            => ['start', 'count', 'user']
        ]
    ],
];
