<?php

namespace App\Listeners;

use App\Jobs\CreateUserWebNotification;

/**
 * Trait HasUserWebNotification
 *
 * @package App\Listeners
 */
trait HasUserWebNotification
{
    /**
     * @var array
     */
    protected $webNotification = [
        'message' => '',
        'meta' => [],
        'recipient_id' => '',
        'type' => 'social',
        '_web_notification' => [],
    ];
    //region Getters

    /**
     * @return array
     */
    public function getWebNotification()
    {
        return $this->webNotification;
    }
    //endregion Getters

    //region Setters
    public function setWebNotification($data = [], $event = '', $channel = '')
    {
        $this->webNotification['message'] = $data['message'] ?? '';
        $this->webNotification['meta'] = $data['data'] ?? [];
        $this->webNotification['recipient_id'] = $channel;
        $this->webNotification['_web_notification'] = [
            'data' => $data['data'] ?? [],
            'event' => $event,
            'channel' => $channel,
        ];
        return $this;
    }
    //endregion Setters

    public function sendWebNotification()
    {
        CreateUserWebNotification::dispatch($this->webNotification);
    }
}
