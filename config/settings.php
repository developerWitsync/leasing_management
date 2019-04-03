<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Auto Generated settings for the users on the registration
    |--------------------------------------------------------------------------
    |
    | This file is for storing the settings that will be created for the users while they register on the Lease Management System
    |
    */
    'lease_payments_basis' => [
        'Turnover Lease',
        'Actual Usage Basis'
    ],
    'lease_assets_number' => [
        1
    ],
    'la_similar_charac_number' => [
        1
    ],
    'no_of_lease_payments' => [
        1,
        2,
        3,
        4
    ],
    'escalation_percentage_settings' => [
        0,
        1
    ],
    'expected_useful_life_of_asset' => [
        -1,
        1,
        5,
        10,
        25
    ],
    'date_format' => 'd-M-Y',
    'complete_previous_steps_error_message' => "You haven't completed the previous steps for the lease, please complete the previous steps first.",
    'lease_already_submitted'   => 'This Lease has been already submitted. Please go to Modify Lease in case of any modifications if required.',
    'file_size_limits' => [
        'max_size_in_kbs' => '2000',
        'max_size_in_mbs' => '2MB',
        'file_validation' => 'Only Doc,Pdf,Docx,Zip with 2MB size of files are allowed.',
        'file_rule'       =>  'file|mimes:doc,pdf,docx,zip|max:2000|nullable',
        'certificates'       =>  'file|required|mimes:doc,pdf,docx,zip|max:2000'
    ],
    'max_lease_asset_number_of_payments' => 10
];
