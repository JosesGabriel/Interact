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
        // $this->base_url = env('JOURNAL_API_URL');
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
        $headers = [
            'Authorization' => "Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJjbGllbnRfbmFtZSI6IjRSQjErUjQ5MyJ9.SZzdF4-L3TwqaGxfb8sR-xeBWWHmGyM4SCuBc1ffWUs"
        ];
        $url = $this->generateUrlFromConfig($config, $data);
        $response = $this->addHeaderRequest($headers)->requestWithClientCreds($data)->request($url, $config['method']);

        return $this->absorbOwnApiResponse($response);
    }
}
