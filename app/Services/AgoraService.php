<?php

namespace App\Services;

use TomatoPHP\LaravelAgora\Services\Token\RtcTokenBuilder;

class AgoraService
{
    protected $appId;
    protected $appCertificate;

    public function __construct()
    {
        $this->appId = config('laravel-agora.app_id');
        $this->appCertificate = config('laravel-agora.app_certificate');

        \Log::debug('AgoraService: AGORA_APP_ID = ' . $this->appId);
        \Log::debug('AgoraService: AGORA_APP_CERTIFICATE = ' . $this->appCertificate);
    }

    public function generateToken($channelName, $uid, $role, $expireTime = 3600)
    {
        if (empty($this->appId) || empty($this->appCertificate)) {
            throw new \Exception('Agora App ID or Certificate is missing from configuration');
        }

        // Map integer role to string role
        $roleMap = [
            1 => 'RoleSubscriber', // Viewer
            2 => 'RolePublisher',  // Broadcaster
        ];
        $roleString = $roleMap[$role] ?? 'RoleSubscriber'; // Default to Subscriber if invalid

        $privilegeExpiredTs = time() + $expireTime;
        return RtcTokenBuilder::build(
            $this->appId,
            $this->appCertificate,
            $channelName,
            (string) $uid, // Ensure UID is a string
            $roleString,
            $privilegeExpiredTs,
            'video' // Default to video type
        );
    }
}
