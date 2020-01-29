<?php

namespace App\Jobs;

use App\Data\Providers\Arbitrage\Gateway\GatewayProvider;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Class CreateUserWebNotification
 *
 * @package App\Jobs
 */
class CreateUserWebNotification
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var array
     */
    private $payload = [];

    /**
     * Create a new job instance.
     *
     * @param array $payload
     */
    public function __construct($payload = [])
    {
        $this->payload = $payload;
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
            'uri' => '/api/notifications/activities',
            'method' => 'POST',
        ], $this->payload);
    }
}
