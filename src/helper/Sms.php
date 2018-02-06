<?php
/**
 * Sms.php
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
 * SMS 전송 클래스
 * phps 모듈 적용
 * 
 * @category Trait
 * @package  PHPS
 * @author   www.phps.kr <helpcenter@phps.kr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://www.phps.kr/smshosting_manual.html
 */
trait Sms
{
    private static $_server_url = 'http://sms.phps.kr/lib/send.sms';
    private static $_cut = 5000;
    private $_params = [
        'TR_ID' => '',
        'TR_KEY' => '',
        'TR_FROM' => '',
        'TR_COMMENT' => '',
        'TR_DATE' => 0
    ];

    /**
     * Set
     * 
     * @param string $key  key
     * @param string $val1 val1
     * @param string $val2 val2
     * @param string $val3 val3
     * 
     * @return bool
     */
    public function set($key = '', $val1 = '', $val2 = '',$val3 = '')
    {
        if ($key === 'TR_TO' && $val1 !== '') {
            $this->_params['TR_TO'][$val1] = [
                'name' => $val2,
                'name2' => $val3
            ];
        } else {
            if (empty($val1)) {
                unset($this->_params[$key]);
            } else {
                $this->_params[$key] = $val1;
            }
        }
        return true;
    }

    /**
     * SendCount
     * 
     * @param array|null $params params
     * 
     * @return string
     */
    public function sendCount($params = null)
    {
        if ($params === null) {
            $params = $this->_params;
        }

        $post = [
            'adminuser' => $params['TR_ID'],
            'authkey' => $params['TR_KEY'],
            'type' => 'view'
        ];

        $return = $this->_curlSend($post);

        return $return;
    }

    /**
     * Cancel
     * 
     * @param array|null $params params
     * 
     * @return string
     */
    public function cancel($params = null)
    {
        if ($params === null) {
            $params = $this->_params;
        }

        $post = [
            'adminuser' => $params['TR_ID'],
            'authkey' => $params['TR_KEY'],
            'date' => date('Y-m-d H:i:s', strtotime('+1 day')),
            'tr_num' => $params['TR_NUM']
        ];

        $return = $this->_curlSend($post);

        return $return;
    }

    /**
     * Send
     * 
     * @param array|null $params params
     * 
     * @return string
     */
    public function send($params = null)
    {
        if ($params === null) {
            $params = $this->_params;
        }

        if (empty($params['TR_TXTMSG'])) {
            return [
                'msg' => 'TR_TXTMSG is empty',
                'status' => 'fail'
            ];
        }

        if (!is_array($params['TR_TO'])) {
            return [
                'msg' => 'TR_TO is not array',
                'status' => 'fail'
            ];
        }

        $tmpto = each($params['TR_TO']);
        if (empty($tmpto[0])) {
            return [
                'msg' => 'TR_TO is empty',
                'status' => 'fail'
            ];
        }

        $phone = '';
        $name = '';
        $cnt = 1;
        $index = 0;
        $group = [];
        $group[$index]['phone'] = '';
        $group[$index]['name'] = '';
        $group[$index]['name2'] = '';

        foreach ($params['TR_TO'] as $key => $val) {
            $group[$index]['phone'] .= preg_replace('/[^0-9]/', '', $key).',';
            $group[$index]['name'] .= preg_replace('/[,]/', '', $val['name']).',';
            $group[$index]['name2'] .= preg_replace('/[,]/', '', $val['name2']).',';
            if ($cnt % self::$_cut == 0) {
                $index += 1;
                $group[$index]['phone'] = '';
                $group[$index]['name']  = '';
                $group[$index]['name2'] = '';
            }
            $cnt += 1;
        }

        $params['TR_COMMENT'] = mb_convert_encoding(
            $params['TR_COMMENT'], 'EUC-KR', 'UTF-8'
        );
        $params['TR_TXTMSG'] = mb_convert_encoding(
            $params['TR_TXTMSG'], 'EUC-KR', 'UTF-8'
        );

        foreach ($group as $key => $pdata) {
            $phone = preg_replace('/,$/', '', $pdata['phone']);
            $name = preg_replace('/,$/', '', $pdata['name']);
            $name2 = preg_replace('/,$/', '', $pdata['name2']);

            $name = mb_convert_encoding($name, 'EUC-KR', 'UTF-8');
            $name2 = mb_convert_encoding($name2, 'EUC-KR', 'UTF-8');

            $post = [
                'adminuser' => $params['TR_ID'],
                'authkey' => $params['TR_KEY'],
                'phone' => $phone,
                'name' => $name,
                'name2' => $name2,
                'rphone' => $params['TR_FROM'],
                'msg' => isset($params['TR_COMMENT']) ? $params['TR_COMMENT'] : '',
                'sms' => $params['TR_TXTMSG'],
                'date' => $params['TR_DATE'],
                'ip' => getenv('REMOTE_ADDR')
            ];

            $return[] = $this->_curlSend($post);
        }

        unset($this->_params);
        return $return;
    }

    /**
     * CRUL
     * 
     * @param array $post post
     * 
     * @return string
     */
    private function _curlSend($post = [])
    {
        $CURL = curl_init(self::$_server_url);
        curl_setopt($CURL, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($CURL, CURLOPT_HEADER, false);
        curl_setopt($CURL, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($CURL, CURLOPT_ENCODING, '');
        curl_setopt($CURL, CURLOPT_USERAGENT, '');
        curl_setopt($CURL, CURLOPT_AUTOREFERER, true);
        curl_setopt($CURL, CURLOPT_CONNECTTIMEOUT, 120);
        curl_setopt($CURL, CURLOPT_TIMEOUT, 120);
        curl_setopt($CURL, CURLOPT_MAXREDIRS, 10);
        curl_setopt($CURL, CURLOPT_POST, 1);
        curl_setopt($CURL, CURLOPT_POSTFIELDS, $post);
        curl_setopt($CURL, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($CURL, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($CURL, CURLOPT_VERBOSE, 0);
        $undate = curl_exec($CURL);
        curl_close($CURL);

        return unserialize($undate);
    }
}
