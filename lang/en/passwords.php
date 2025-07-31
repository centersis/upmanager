<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Password Reset Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are the default lines which match reasons
    | that are given by the password broker for a password update attempt
    | has failed, such as for an invalid token or invalid new password.
    |
    */

    'reset' => 'Your password has been reset.',
    'sent' => 'We have emailed your password reset link.',
    'throttled' => 'Please wait before retrying.',
    'token' => 'This password reset token is invalid.',
    'user' => "We can't find a user with that email address.",
    
    // Laravel default messages
    'Whoops!' => 'Whoops!',
    'Hello!' => 'Hello!',
    'Regards,' => 'Regards,',
    
    // Email notifications
    'reset_password_notification' => [
        'subject' => 'Reset Password Notification',
        'greeting' => 'Hello!',
        'line_1' => 'You are receiving this email because we received a password reset request for your account.',
        'action' => 'Reset Password',
        'line_2' => 'This password reset link will expire in :count minutes.',
        'line_3' => 'If you did not request a password reset, no further action is required.',
        'salutation' => 'Regards,<br>:app_name',
        'trouble_clicking' => "If you're having trouble clicking the \":actionText\" button, copy and paste the URL below\ninto your web browser:",
    ],
]; 