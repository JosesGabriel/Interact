<?php

namespace App\Data\Providers\Arbitrage\Stream;

use App\Data\Providers\BaseProvider;

/**
 * Class StreamProvider
 *
 * @package App\Data\Providers\Arbitrage\Stream
 */
class StreamProvider extends BaseProvider
{
    /**
     * StreamProvider constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->base_url = env('STREAM_API_URL');
        $this->client_id = env('STREAM_API_CLIENT_ID');
        $this->client_secret = env('STREAM_API_CLIENT_SECRET');

    }

    /**
     * @param array $config
     * @param array $data
     * @return \Arbitrage\Abstracts\Providers\Provider|mixed
     * @throws \Arbitrage\Exceptions\ProviderException
     */
    public function handle(array $config, array $data = [])
    {
        $url = $this->generateUrlFromConfig($config, $data);
        $response = $this->requestWithClientCreds($data)->request($url, $config['method']);

        return $this->absorbOwnApiResponse($response);

    }
}
