<?php 
namespace Amir\Notifications\Services;

use Amir\Notifications\Services\FcmHelper;
use GuzzleHttp\Client;

/**
 * Summary of Notification Interface 
 */
abstract class NotificationService {

    /**
     * client
     * @var mixed
     */
    protected $client;

    /**
     * projectId
     * @var string
     */
    protected $projectId;

    /**
     * url of request
     * @var string
     */
    public $url;


    /**
     * headers of request
     * @var array
     */
    public $headers;


    public function __construct()
    {
        
        $this->client = new Client;
        $this->projectId = config('fcm.project-id');
        $this->url = "https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send";
        $this->headers = [
            'Authorization' => 'Bearer ' . $this->getAccessToken(),
            'Content-Type' => 'application/json',
        ];
    }




    /**
     * get Access Token
     * @return mixed
     */
    public function getAccessToken()
    {
        return FcmHelper::configureClient();
    }



    /**
     * config message to send
     *
     * @param mixed $message
     * @param mixed $token
     * @return array
     */
    public function configMessage($message, $token)
    {
        if (!is_array($message)) {
            $message = $message->toArray();
        }

        return [
            'message' => [
                'notification' => [
                    'title' => $message['title'],
                    'body' => $message['message'],
                    'image' => $message['image'] ? $message['image'] : '',
                ],
                'android' => [
                    'notification' => [
                        'sound' => 'default'
                    ]
                ],
                'apns' => [
                    'payload' => [
                        'aps' => [
                            'sound' => 'default'
                        ]
                    ]
                ],
                'token' => $token,
            ]
        ];

    }


    








}
