<?php

namespace App\Data\Providers\Arbitrage\Notifications;

use App\Data\Providers\BaseProvider;

/**
 * Class NotificationProvider
 *
 * @package App\Data\Providers\Arbitrage\Notifications
 */
class NotificationProvider extends BaseProvider
{
    /**
     * GameProvider constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->base_url = env('NOTIFICATION_API_URL');
        $this->client_id = env('NOTIFICATION_API_CLIENT_ID');
        $this->client_secret = env('NOTIFICATION_API_CLIENT_SECRET');
    }

    /**
     * @param array $config
     * @param array $data
     * @return \Arbitrage\Abstracts\Providers\Provider
     * @throws \Arbitrage\Exceptions\ProviderException
     */
    public function handle(array $config = [], array $data = [])
    {
        $url = $this->generateUrlFromConfig($config, $data);
        $response = $this->requestWithClientCreds($data)->request($url, $config['method']);

        return $this->absorbOwnApiResponse($response);
    }
}
