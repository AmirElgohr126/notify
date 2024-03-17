<?php
namespace Amir\Notifications\Notifications;

use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
use Amir\Notifications\Services\NotificationService;
use Amir\Notifications\Contracts\NotificationInterface;

class Sender extends NotificationService implements NotificationInterface
{

    /**
     * send one notify to one Device
     * @param mixed $notification
     * @param mixed $deviceToken
     * @return array
     */
    public function sendToOneDevice($notification, $deviceToken)
    {
        try {
            $postData = $this->configMessage($notification, $deviceToken);
            $response = $this->client->post($this->url, [
                'json' => $postData,
                'headers' => $this->headers,
            ]);
            $responseData = json_decode($response->getBody()->getContents(), true);
            return $responseData;
        } catch (\GuzzleHttp\Exception\GuzzleException $e) {
            throw new \Exception('Failed to send notification: ' . $e->getMessage());
        }
    }



    /**
     * send notification to many devices
     *
     * @param mixed $notification
     * @param mixed $deviceTokens
     */
    public function sendToManyDevice($message, $deviceTokens)
    {
        $url = $this->url;
        $requests = function ($deviceTokens) use ($message, $url) {
            foreach ($deviceTokens as $token) {
                $postData = $this->configMessage($message, $token);
                yield new Request('POST', $url, $this->headers, json_encode($postData));
            }
        };
        $successfulTokens = [];
        $failedTokens = [];
        $pool = new Pool($this->client, $requests($deviceTokens), [
            'concurrency' => config('fcm.concurrency'),
            'fulfilled' => function ($response, $index) use (&$successfulTokens, &$failedTokens, $deviceTokens) {
                $statusCode = $response->getStatusCode();
                $responseData = json_decode($response->getBody()->getContents(), true);
                // Check HTTP status code for success
                if ($statusCode == 200) {
                    // If 'name' or 'message_id' is present, consider it a success
                    if (isset ($responseData['name']) || (isset ($responseData['results']) && isset ($responseData['results'][0]['message_id']))) {
                        $successfulTokens[] = $deviceTokens[$index];
                    } else {
                        // If 'name' or 'message_id' is not present, consider it a failure.
                        // There may be an error message in the response.
                        $errorMessage = isset ($responseData['error']) ? $responseData['error'] : 'Error without detail';
                        $failedTokens[$deviceTokens[$index]] = $errorMessage;
                    }
                } else {
                    // Non-200 responses are treated as failures.
                    $errorMessage = isset ($responseData['error']) ? $responseData['error'] : 'Error without detail';
                    $failedTokens[$deviceTokens[$index]] = $errorMessage;
                }
            },
            'rejected' => function ($reason, $index) use (&$failedTokens, $deviceTokens) {
                // Handle network-level errors or other Guzzle exceptions
                $errorMessage = $reason instanceof Exception ? $reason->getMessage() : 'Request rejected without exception';
                $failedTokens[$deviceTokens[$index]] = $errorMessage;
            },
        ]);
        $promise = $pool->promise();
        $promise->wait();
        return [
            'successful' => count($successfulTokens),
            'failed' => count($failedTokens),
            'successfulTokens' => $successfulTokens,
            'failedTokens' => $failedTokens
        ];
    }
}