<?php 
namespace helper;

/**
 * Android, iOS 푸시 전송 클래스
 * PushNotification::android($data, $ids, $type);
 * PushNotification::ios($data, $ids);
 */
class PushNotification
{
    // Android API access key from Google API's Console.
    private static $GCM_API_ACCESS_KEY = 'AIzaSyDv9fm93XeeH2pxfl4GMzjBXokAo13WnYw';
    private static $FCM_API_ACCESS_KEY = 'AIzaSyDv9fm93XeeH2pxfl4GMzjBXokAo13WnYw';

    // iOS pem file, Private key's passphrase.
    private static $PEM = 'ck.pem';
    private static $PASSPHRASE = '1231';

    /**
     * android 유저에게 푸시를 전송
     * 포트 443 outbound 오픈 필요
     * @param array $data [title, body]
     * @param array|string $ids
     * @param string $type gcm|fcm
     * @return array $result
     */
    public static function android($data, $ids, $type = 'gcm')
    {
        if ($type === 'fcm') {
            $url = 'https://fcm.googleapis.com/fcm/send';
            $API_ACCESS_KEY = self::$FCM_API_ACCESS_KEY;
            $recipientKey = is_string($ids) ? 'to' : 'registration_ids';
        } else {
            $url = 'https://android.googleapis.com/gcm/send';
            $API_ACCESS_KEY = self::$GCM_API_ACCESS_KEY;
            $recipientKey = 'registration_ids';
            $ids = is_string($ids) ? (array)$ids : $ids;
        }

        $fields = [
            $recipientKey => $ids,
            'data' => [
                'alert' => $data
            ]
        ];

        $postfields = json_encode($fields, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);

        $headers = [
            'Content-Type: application/json',
            'Authorization: key='.$API_ACCESS_KEY
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
        $result = json_decode(curl_exec($ch));

        if ($result === FALSE) {
            return curl_error($ch);
        }

        curl_close($ch);

        return $result;
    }

    /**
     * iOS 유저에게 푸시를 전송
     * ck.pem 인증서 파일 필요
     * 포트 2195 outbound 오픈 필요
     * @param array $data [title, message]
     * @param string $token
     * @return string $result
     */
    public static function ios($data, $token)
    {
        $url = 'ssl://gateway.sandbox.push.apple.com:2195';

        $ctx = stream_context_create();

        stream_context_set_option($ctx, 'ssl', 'local_cert', self::$PEM);
        stream_context_set_option($ctx, 'ssl', 'passphrase', self::$PASSPHRASE);

        $fp = stream_socket_client(
            $url, $err, $errstr, 60,
            STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx
        );

        if (!$fp) {
            return "Failed to connect: $err $errstr" . PHP_EOL;
        }

        $body['aps'] = [
            'alert' => $data,
            'sound' => 'default',
        ];
        $payload = json_encode($body);

        $msg = chr(0) . pack('n', 32) . pack('H*', $token) . pack('n', strlen($payload)) . $payload;
        $result = fwrite($fp, $msg, strlen($msg));
        fclose($fp);

        return $result;
    }
}
