<?php

namespace App\Listeners;

use App\Data\Providers\BaseProvider;

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
        '_notification' => [],
    ];

    /**
     * @var BaseProvider
     */
    protected $webNotificationProvider;

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
        $this->webNotification['meta'] = $data;
        $this->webNotification['recipient_id'] = $channel;
        $this->webNotification['_notification'] = compact('data', 'event', 'channel');
        return $this;
    }

    /**
     * @param BaseProvider $provider
     * @return $this
     */
    public function setWebNotificationProvider(BaseProvider $provider)
    {
        $this->webNotificationProvider = $provider;
        return $this;
    }
    //endregion Setters

    public function sendWebNotification()
    {
        if ($this->webNotificationProvider) {
            $this->webNotificationProvider->handle([
                'uri' => '/api/notifications/activities',
                'method' => 'POST',
            ], $this->webNotification);
        }
    }
}
