<?php 
/**
 * PushNotification.php
 * 
 * PHP Version 7
 * 
 * @category Helper
 * @package  CPFS
 * @author   gshn <gs@gs.hn>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/gshn/cpfs
 */
namespace helper;

/**
 * Android, iOS 푸시 전송 클래스
 * PushNotification::firebase($data, $ids, $type);
 * PushNotification::android($data, $ids, $type);
 * PushNotification::ios($data, $ids);
 * 
 * @category Trait
 * @package  CPFS
 * @author   gshn <gs@gs.hn>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/gshn/cpfs
 */
trait PushNotification
{
    // Google Firebase API KEY
    public static $FIREBASE_KEY = 'AIzaSyBLq6WFShoUwRGD9AyPdubNnCv41IL-QVg';

    // Android API access key from Google API's Console.
    public static $GCM_KEY = 'AIzaSyDv9fm93XeeH2pxfl4GMzjBXokAo13WnYw';

    // iOS pem file, Private key's passphrase.
    public static $PEM = HELPER.'/ck.pem';
    public static $PASSPHRASE = '1231';

    /**
     * Postfields JSON 코드 변환
     * 
     * @param array $array postfileds
     * 
     * @return string $jsonstring
     */
    private static function _postfields($array)
    {
        return json_encode(
            $array, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT |
            JSON_HEX_AMP | JSON_UNESCAPED_UNICODE
        );
    }

    /**
     * Curl send
     * 
     * @param string $url        전송URL
     * @param array  $header     전송해더
     * @param string $postfields 전송값
     * 
     * @return mixed
     */
    private static function _send($url, $header, $postfields)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
        $result = json_decode(curl_exec($ch));

        if ($result === false) {
            return curl_error($ch);
        }

        curl_close($ch);

        return $result;
    }

    /**
     * Google Firebase 이용 푸시 전송
     * 안드로이드 아이폰 둘 다 대응 함
     * 
     * @param array|string $ids      push tokens
     * @param array        $alert    [title, body]
     * @param array|null   $callback push 후 콜백
     * 
     * @return array $result
     */
    public static function firebase($ids, $alert, $callback = null)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';

        $header = [
            'Content-Type: application/json',
            'Authorization: key='.self::$FIREBASE_KEY
        ];

        if (is_array($ids)) {
            $recipientKey = 'registration_ids';
        } else {
            $recipientKey = 'to';
            $ids = (string)$ids;
        }

        $fields = [
            $recipientKey => $ids,
            'data' => [
                'alert' => $alert,
                'callback' => $callback
            ]
        ];

        $postfields = self::_postfields($fields);

        return self::_send($url, $header, $postfields);
    }

    /**
     * Android 유저에게 푸시를 전송
     * 포트 443 outbound 오픈 필요
     * 
     * @param array|string $ids      push tokens
     * @param array        $alert    [title, body]
     * @param array|null   $callback push 후 콜백
     * 
     * @return array $result
     */
    public static function android($ids, $alert, $callback = null)
    {
        $url = 'https://android.googleapis.com/gcm/send';

        $header = [
            'Content-Type: application/json',
            'Authorization: key='.self::$GCM_KEY
        ];

        $ids = is_array($ids) ? (string)$ids : (array)$ids;

        $fields = [
            'registration_ids' => $ids,
            'data' => [
                'alert' => $alert,
                'callback' => $callback
            ]
        ];

        $postfields = self::_postfields($fields);

        return self::_send($url, $header, $postfields);
    }

    /**
     * 아이폰 유저에게 푸시를 전송
     * ck.pem 인증서 파일 필요
     * 포트 2195 outbound 오픈 필요
     * 
     * @param string     $token    push token
     * @param array      $alert    [title, body]
     * @param array|null $callback push 후 콜백
     * 
     * @return string $result
     */
    public static function ios($token, $alert, $callback = null)
    {
        if (DEV) {
            $url = 'ssl://gateway.sandbox.push.apple.com:2195';
        } else {
            $url = 'ssl://gateway.push.apple.com:2195';
        }

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
            'alert' => $alert,
            'callback' => $callback,
            'sound' => 'default'
        ];
        $payload = json_encode($body);

        $msg = chr(0).pack('n', 32).pack('H*', $token).
            pack('n', strlen($payload)).$payload;
        $result = fwrite($fp, $msg, strlen($msg));
        fclose($fp);

        return $result;
    }
}
