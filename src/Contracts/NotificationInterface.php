<?php 
namespace Amir\Notifications\Contracts;

/**
 * Summary of Notification Interface 
 */
interface NotificationInterface
{
    /**
     * send one notify to one Device
     * @param mixed $notification
     * @param mixed $deviceToken
     * @return array
     */
    public function sendToOneDevice($notification, $deviceToken);

    /**
     * send notification to many devices
     *
     * @param mixed $notification
     * @param mixed $deviceTokens
     */
    public function sendToManyDevice($message, $deviceTokens);
}

