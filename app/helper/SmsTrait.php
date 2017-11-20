<?php
trait SmsTrait
{
    private $server_url = 'http://sms.phps.kr/lib/send.sms';
    private $cut = 5000;
    private $params = [
        'TR_ID' => 'gs126997',
        'TR_KEY' => 'BTRDA5SG4#',
        'TR_FROM' => '010-6264-1185',
        'TR_COMMENT' => '접촉경고',
        'TR_DATE' => 0
    ];

    protected $curcount = 0;

    public function get()
    {
        return $this->params;
    }

    public function set($key = '', $val1 = '', $val2 = '',$val3 = '')
    {
        if ($key === 'TR_TO' && $val1 !== '') {
            $this->params['TR_TO'][$val1] = [
                'name' => $val2,
                'name2' => $val3
            ];
        } else {
            if (empty($val1)) {
                unset($this->params[$key]);
            } else {
                $this->params[$key] = $val1;
            }
        }
        return true;
    }

    public function sendCount($params = null)
    {
        if ($params === null) {
            $params = $this->params;
        }

        $post = [
            'adminuser' => $params['TR_ID'],
            'authkey' => $params['TR_KEY'],
            'type' => 'view'
        ];

        $return = $this->curl_send($post);

        return $return;
    }

    public function cancel($params = null)
    {
        if ($params === null) {
            $params = $this->params;
        }

        $post = [
            'adminuser' => $params['TR_ID'],
            'authkey' => $params['TR_KEY'],
            'date' => date('Y-m-d H:i:s', strtotime('+1 day')),
            'tr_num' => $params['TR_NUM']
        ];

        $return = $this->curl_send($post);

        return $return;
    }

    public function send($params = null) {
        if ($params === null) {
            $params = $this->params;
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
        $group[$index]['phone'] = $group[$index]['name'] = $group[$index]['name2'] = '';

        foreach ($params['TR_TO'] as $key => $val) {
            $group[$index]['phone'] .= preg_replace('/[^0-9]/', '', $key).',';
            $group[$index]['name'] .= preg_replace('/[,]/', '', $val['name']).',';
            $group[$index]['name2'] .= preg_replace('/[,]/', '', $val['name2']).',';
            if ($cnt % $this->cut == 0) {
                $index += 1;
                $group[$index]['phone'] = $group[$index]['name']  = $group[$index]['name2'] = '';
            }
            $cnt += 1;
        }

        $params['TR_COMMENT'] = mb_convert_encoding($params['TR_COMMENT'], 'EUC-KR', 'UTF-8');
        $params['TR_TXTMSG'] = mb_convert_encoding($params['TR_TXTMSG'], 'EUC-KR', 'UTF-8');

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

            $return[] = $this->curl_send($post);
        }

        unset($this->params);
        return $return;
    }

    private function curl_send($post = []) {
        $CURL = curl_init($this->server_url);
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
