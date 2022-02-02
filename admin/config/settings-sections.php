<?php

return [
    'mf_options' => [
        'label'     => 'Settings',
        'callback'  => 'general_options',
        'page'      => 'mf_settings_section',
        'fields'    => [
            'mf_user' => [
                'option_group'      => 'mf_settings_group',
                'label'             => 'DB User',
                'type'              => 'text',
                'is_mandatory'      => true,
            ],
            'mf_pass'     => [
                'option_group'      => 'mf_settings_group',
                'label'             => 'DB Password',
                'type'              => 'text',
                'is_mandatory'      => true,
            ],
            'mf_server'     => [
                'option_group'      => 'mf_settings_group',
                'label'             => 'DB Server',
                'type'              => 'text',
                'is_mandatory'      => true,
            ],
            'mf_database'     => [
                'option_group'      => 'mf_settings_group',
                'label'             => 'DB Name',
                'type'              => 'text',
                'is_mandatory'      => true,
            ],
            'mf_collection'     => [
                'option_group'      => 'mf_settings_group',
                'label'             => 'DB Collection',
                'type'              => 'text',
                'is_mandatory'      => true,
            ],
            'mf_number_of_post'     => [
                'option_group'      => 'mf_settings_group',
                'label'             => 'Amount of post to Sync',
                'type'              => 'number',
                'is_mandatory'      => false,
            ],
        ],
    ],
];