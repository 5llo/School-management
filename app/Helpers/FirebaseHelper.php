<?php

use Illuminate\Support\Facades\Http;

 function sendNotificationFCM()
{
    $deviceToken = 'ضع هنا التوكن اللي بيجي من تطبيق Flutter';

    // تحميل ملف JSON
    $jsonKeyPath = storage_path('firebase-service-account.json');
    $clientEmail = json_decode(file_get_contents($jsonKeyPath), true)['client_email'];
    $privateKey = json_decode(file_get_contents($jsonKeyPath), true)['private_key'];

    // توليد Access Token من Google OAuth
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

    // التوقيع
    openssl_sign($data, $signature, $privateKey, 'sha256WithRSAEncryption');
    $signedJWT = $data . '.' . base64_encode($signature);

    // الحصول على access token
    $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
        'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
        'assertion' => $signedJWT,
    ]);

    $accessToken = $response->json()['access_token'];

    // إرسال الإشعار باستخدام access token
    $projectId = 'fluttercourse-c08c1'; // غيره لو اسم مشروعك مختلف
    $fcmUrl = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";

    $response = Http::withToken($accessToken)
        ->post($fcmUrl, [
            'message' => [
                'token' => $deviceToken,
                'notification' => [
                    'title' => 'عنوان الإشعار',
                    'body' => 'محتوى الإشعار',
                ],
                'data' => [
                    'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                    'type' => 'type',
                    'id' => 'userId',
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
