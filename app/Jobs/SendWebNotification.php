<?php

namespace App\Jobs;

use App\Data\Providers\Arbitrage\Gateway\GatewayProvider;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Class SendWebNotification
 *
 * @package App\Jobs
 */
class SendWebNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $payload = [];

    /**
     * Create a new job instance.
     *
     * @param array $data
     * @param string $event
     * @param string $channel
     */
    public function __construct($data = [], $event = '', $channel = 'all')
    {
        $this->payload = compact('data', 'event', 'channel');
    }

    /**
     * Execute the job.
     *
     * @param GatewayProvider $gatewayProvider
     * @return void
     * @throws \Arbitrage\Exceptions\ProviderException
     */
    public function handle(GatewayProvider $gatewayProvider)
    {
        $gatewayProvider->handle([
            'uri' => '/api/stream/v1/sse/notify',
            'method' => 'POST',
        ], $this->payload);
    }
}
