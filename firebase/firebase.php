<?php
require __DIR__.'/vendor/autoload.php';

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

// Create a factory object for your Firebase project
$factory = (new Factory)->withServiceAccount('/path/to/serviceAccountKey.json');

// Create a messaging API instance
$messaging = $factory->createMessaging();

// Construct the message to send
$message = CloudMessage::withTarget('phone', '+1234567890')
    ->withNotification(Notification::create('Test message', 'This is a test message.'));

// Send the message
$messaging->send($message);

echo 'Message sent!';
?>
