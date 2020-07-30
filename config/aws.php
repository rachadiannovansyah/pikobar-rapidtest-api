<?php

return [
    'region' => env('AWS_DEFAULT_REGION'),

    'key' => env('AWS_ACCESS_KEY_ID'),

    'secret' => env('AWS_SECRET_ACCESS_KEY'),

    'sns' => [
        'topics' => [
            'applicant_registered' => env('AWS_SNS_TOPIC_APPLICANT_REGISTERED'),
            'applicant_invited' => env('AWS_SNS_TOPIC_APPLICANT_INVITED'),
            'applicant_test_result_save' => env('AWS_SNS_TOPIC_APPLICANT_TEST_RESULT_SAVE'),
        ]
    ],
];
