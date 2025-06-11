<?php

namespace App\Http\Controllers\notification;
use Illuminate\Support\Facades\Http;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class firebaseController extends Controller
{

  public static function sendToUserFCM($deviceToken, $title, $body)
    {
        // تحميل بيانات الخدمة من ملف JSON
        $jsonKeyPath = storage_path('firebase_credentials.json');
        $serviceAccount = json_decode(file_get_contents($jsonKeyPath), true);
        $clientEmail = $serviceAccount['client_email'];
        $privateKey = $serviceAccount['private_key'];

        // توليد JWT
        $jwtHeader = base64_encode(json_encode(['alg' => 'RS256', 'typ' => 'JWT']));
        $now = time();
        $claims = [
            'iss' => $clientEmail,
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
            'aud' => 'https://oauth2.googleapis.com/token',
            'iat' => $now,
            'exp' => $now + 3600,
        ];
        $jwtClaim = base64_encode(json_encode($claims));
        $data = $jwtHeader . '.' . $jwtClaim;

        // توقيع الـ JWT
        openssl_sign($data, $signature, $privateKey, 'sha256WithRSAEncryption');
        $signedJWT = $data . '.' . base64_encode($signature);

        // الحصول على access token
        $tokenResponse = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $signedJWT,
        ]);

        if (!$tokenResponse->successful()) {
            return response()->json(['error' => 'Failed to generate access token'], 500);
        }

        $accessToken = $tokenResponse->json()['access_token'];

        // إعداد رابط FCM
        $projectId = 'fluttercourse-c08c1'; // غيّر اسم المشروع إن لزم
        $fcmUrl = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";

        // إرسال الإشعار
        $response = Http::withToken($accessToken)
            ->post($fcmUrl, [
                'message' => [
                    'token' => $deviceToken,
                    'notification' => [
                        'title' => $title,
                        'body' => $body,
                    ],
                    'data' => [
                        'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                        'type' => 'user_login', // تقدر تغيرها حسب الحاجة
                        'id' => 'any_id',
                    ],
                    'android' => [
                        'notification' => [
                            'sound' => 'default',
                            'notification_priority' => 'PRIORITY_MAX',
                        ]
                    ],
                    'apns' => [
                        'payload' => [
                            'aps' => [
                                'content_available' => true,
                            ]
                        ]
                    ]
                ]
            ]);

        return $response->json();
    }
}
