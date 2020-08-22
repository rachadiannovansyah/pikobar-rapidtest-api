<?php

return [
    /**
     * In development/staging environment, we need to carefully send notifications.
     * Redirect/send to developer instead to real numbers
     */
    'notify_to' => env('NOTIFY_TO'),
];
