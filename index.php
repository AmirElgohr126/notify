<?php

require __DIR__ . '/vendor/autoload.php';




use Amir\Notifications\Notifications\Sender;

$notification = [
    'title' => 'Your Notification Title',
    'message' => 'Your notification message.',
    'image' => 'Optional image URL',
];

$deviceToken = 'Your Device Token Here';

$sender = new Sender();
$response = $sender->sendToOneDevice($notification, $deviceToken);