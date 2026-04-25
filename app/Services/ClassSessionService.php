<?php

namespace App\Services;

use App\Models\ClassSession;

/**
 * Class ClassSessionService
 * @package App\Services
 */
class ClassSessionService
{
    public function createClassSession(array $data)
    {
        $qrCode = str()->random(20);
        $expiredAt = now()->addMinutes($data['code_duration']);
        $classSession = ClassSession::create([
            'class_id' => $data['class_id'],
            'week' => $data['week'],
            'code_duration' => $data['code_duration'],
            'qr_token' => $qrCode,
            'expired_at' => $expiredAt,
            'is_active' => true,
        ]);
        return $classSession;
    }

    public function updateClassSession(array $data)
    {

    }
}
