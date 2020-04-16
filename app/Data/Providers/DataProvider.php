<?php


namespace App\Data\Providers;


use App\Data\Providers\BaseProvider;

/**
 * Class JournalProvider
 *
 * @package App\Data\Providers\Arbitrage\Journal
 */
class DataProvider extends BaseProvider
{
    /**
     * JournalProvider constructor.
     */
    public function __construct()
    {
        parent::__construct();

        // ask data from carl
        $this->base_url = env('GATEWAY_API_URL')."/api/data";
        // $this->client_id = env('JOURNAL_API_CLIENT_ID');
        // $this->client_secret = env('JOURNAL_API_CLIENT_SECRET');


    }

    /**
     * @param array $config
     * @param array $data
     * @return \Arbitrage\Abstracts\Providers\Provider
     * @throws \Arbitrage\Exceptions\ProviderException
     */
    public function handle(array $config, array $data = [])
    {
        $config['method'] = 'GET';
        $url = $this->generateUrlFromConfig($config, $data);
        
        $this->request_client->setHeaders([
            // 'content-type' => 'application/json',
            'M-Lyduz-Target-ID' => $this->client_id,
            'M-Lyduz-Target-Secret' => $this->client_secret,
        ]);
       
        $response = $this->request($url, $config['method']);
        return $this->absorbOwnApiResponse($response);
    }
}
