<?php

namespace App\Services;

//  use TomatoPHP\LaravelAgora\Services\AgoraTokenService;
use App\Services\AgoraTokenService;

class AgoraService
{
    protected $agoraService;

    public function __construct(AgoraTokenService $agoraService)
    {
        $this->agoraService = $agoraService;
    }

    public function generateToken($channelName, $uid, $role, $expireTime = 3600)
    {
        return $this->agoraService->generateRtcToken(
            channelName: $channelName,
            uid: $uid,
            role: $role, // 2 for Publisher (broadcaster), 1 for Subscriber (viewer)
            expireTime: $expireTime
        );
    }
}
