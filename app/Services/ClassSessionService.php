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
         $session = ClassSession::where('class_id', $data['class_id'])
                ->where('week', $data['week'])
                ->first();

            if($session){
                $classSession = $this->updateClassSession($data, $session);
                return $classSession;
            }else{
                $classSession = $this->createNewClassSession($data);
                return $classSession;
            }
    }


    public function createNewClassSession(array $data)
    {
        $qrCode = str()->random(50);
        $expiredAt = now()->addMinutes((int)$data['code_duration']);
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

    public function updateClassSession(array $data, ClassSession $session)
    {
        $qrCode = $session->qr_token ?? str()->random(50);
        $expiredAt = now()->addMinutes((int)$data['code_duration']);
        $session->update([
            'code_duration' => $data['code_duration'],
            'qr_token' => $qrCode,
            'expired_at' => $expiredAt,
            'is_active' => true,
        ]);
        return $session;
    }
}
