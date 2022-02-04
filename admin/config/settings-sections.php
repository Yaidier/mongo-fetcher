<?php

return [
    'mf_options' => [
        'label'     => 'DB Settings',
        'callback'  => 'general_options',
        'page'      => 'mf_settings_section',
        'fields'    => [
            'mf_user' => [
                'option_group'      => 'mf_settings_group',
                'label'             => 'User',
                'type'              => 'text',
                'is_mandatory'      => true,
            ],
            'mf_pass'     => [
                'option_group'      => 'mf_settings_group',
                'label'             => 'Password',
                'type'              => 'text',
                'is_mandatory'      => true,
            ],
            'mf_server'     => [
                'option_group'      => 'mf_settings_group',
                'label'             => 'Server',
                'type'              => 'text',
                'is_mandatory'      => true,
            ],
            'mf_database'     => [
                'option_group'      => 'mf_settings_group',
                'label'             => 'Name',
                'type'              => 'text',
                'is_mandatory'      => true,
            ],
            'mf_collection'     => [
                'option_group'      => 'mf_settings_group',
                'label'             => 'Collection',
                'type'              => 'text',
                'is_mandatory'      => true,
            ],
        ],
    ],
    'mf_sync_options' => [
        'label'     => 'Sync Options',
        'callback'  => 'general_options',
        'page'      => 'mf_settings_cron_section',
        'fields'    => [
            'mf_number_of_post'     => [
                'option_group'      => 'mf_settings_group',
                'label'             => 'Amount of post to Sync',
                'type'              => 'number',
                'is_mandatory'      => false,
            ],
            'mf_frecuency_field' => [
                'option_group'      => 'mf_settings_group',
                'label'             => 'Frequency',
                'type'              => 'select',
                'options'           => [
                    'None'              => 0, 
                    'Daily'             => 1, 
                    'Every Two Days'    => 2,
                ],
                'is_mandatory'      => false,
            ],
            'mf_time_field' => [
                'option_group'      => 'mf_settings_group',
                'label'             => 'Hour to run',
                'type'              => 'time',
                'is_mandatory'      => false,
            ],
        ],
    ],
];