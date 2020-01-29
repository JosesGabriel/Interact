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
     * The id of the user that caused this action/notification
     * @return mixed|string
     */
    public function getExecutorId()
    {
        $executor =  $this->webNotification['meta']['user'] ?? ['uuid' => ''];
        return $executor['uuid'] ?? '';
    }

    /**
     * The id of the user that will receive the notification
     * @return mixed
     */
    public function getRecipientId()
    {
        return $this->webNotification['recipient_id'];
    }

    /**
     * @return array
     */
    public function getWebNotification()
    {
        return $this->webNotification;
    }
    //endregion Getters

    //region Setters
    /**
     * @param array $data
     * @param string $event
     * @param string $channel
     * @return $this
     */
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
        if ($this->getRecipientId() != $this->getExecutorId()) {
            CreateUserWebNotification::dispatch($this->webNotification);
        }
    }
}
