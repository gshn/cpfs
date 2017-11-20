<?php
trait GpsTrait
{
    public function coord2int($point)
    {
        return round($point, 7) * 10000000;
    }

    public function int2coord($point)
    {
        return round($point / 10000000, 7);
    }

    public function coord2addr($lat, $lng)
    {
        $addr = [];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://dapi.kakao.com/v2/local/geo/coord2address.json?x='.$lng.'&y='.$lat.'&input_coord=WGS84');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: KakaoAK '.KAKAO_REST_KEY));
        $rst = json_decode(curl_exec($ch));

        if ($rst->meta->total_count > 0) {
            @$addr['fulladdr'] = $rst->documents[0]->road_address->address_name;

            if (empty($addr['fulladdr'])) {
                $addr['fulladdr'] = $rst->documents[0]->address->address_name;
            }
            $addr['addr1'] = $rst->documents[0]->address->region_1depth_name;
            $addr['addr2'] = $rst->documents[0]->address->region_2depth_name;
            $addr['addr3'] = $rst->documents[0]->address->region_3depth_name;
        }
        return $addr;
    }

    /**
     * @brief 두 좌표의 미터식 거리를 측정
     * 지구의 radius값은 6378137로 규정 (위키에서 발췌)
     * @author hheo (hheo@cozmoworks.com)
     * @param float $lat1 좌표1의 lat 값
     * @param float $lng1 좌표1의 lng 값
     * @param float $lat2 좌표2의 lat 값
     * @param float $lng2 좌표2의 lng 값
     * @param m|k $unit 미터단위, 킬로미터단위
     * @return float $distance 거리
     */
    public function distance($lat1, $lng1, $lat2, $lng2, $unit = null)
    {
        // 미터 기준 지구 radius 값
        $radius = 6378137;
        $dlng = $lng1 - $lng2;
        $distance = acos( sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($dlng)) ) * $radius;

        if ($unit === 'm') {
            return round($distance);
        } else if ($unit === 'k') {
            return round($distance / 1000, 2);
        } else {
            return $distance;
        }
    }

    /**
     * @brief 두 좌표값과 특정 범위와 특정 각도를 이용해서 꼭지점 4개를 리턴
     * 직선 백터는 작은 x 값에서 큰 x 값으로 진행으로 변경함
     * 한국 GPS 좌표계는 1-4분면만 사용하므로 음수 값이 없다고 가정
     * 두 좌표를 이용해서 기울기를 구하고 해당 기울기의 아크탄젠트를 이용해서 실제 각도를 구함
     * 각도가 1보다 작거나 89보다 크거나 하는 극단적인 경우에 에러가 발생하므로 해당 상황에 대한 예외처리를 함
     * 두 좌표간을 연결하는 직선을 만들어서 $range 만큼 연장한 곳의 x 값을 찾기 위한 루프 탐색을 수행
     * $range가 길면 길 수록 확인해야 하는 값이 너무 많아지므로 거리가 -1미터 이상 차이 날 경우 $distance - $range 값을 이용해서 skip을 수행
     * 두 좌표간이 극단적으로 길거나 $range가 극단적으로 길 경우 반감기를 사용해서 최소한의 횟수로 탐색을 수행
     * 만약 오차거리 1미터 이하로 탐색을 하지 못했을 경우 최근 가장 짧았던 거리의 좌표를 버퍼에 저장해서 에러가 나지 않게 함
     * 실제 GPS 좌표 값이 미터 단위에 정비례하지 않으므로 임의의 상수 0.00001을 곱해서 탐색에 활용
     * 직선 방정식을 구했어도 지구 표면은 평면이 아니므로 오차가 발생할 수 있음을 감안해야 함
     * 오차거리 1미터 이내로 근접 했을 때 탐색을 마치고 해당 위치를 기준으로 회전변환을 수행
     * 회전변환 또한 평면일 때 대응하는 공식이므로 실제 GPS상에서는 오차가 발생 됨
     * 회전변환 좌표는 테스트 결과 10%정도로 거리가 증가하는 현상이 있고 각도도 미세하게 원하는 각도하고는 차이가 있음
     * 오차 값이 사용상에 문제가 될 수준은 아님
     * @author hheo (hheo@cozmoworks.com)
     * @param float $y1 좌표1의 lat 값
     * @param float $x1 좌표1의 lng 값
     * @param float $y2 좌표2의 lat 값
     * @param float $x2 좌표2의 lng 값
     * @param int $range 좌표로 부터 떨어져 있는 정도 (미터단위)
     * @param int $angle 좌표로 부터 떨어져 있는 각도 (도단위)
     * @return array 꼭지점 4개의 lat, lng 좌표
    */
    public function getSquare($y1, $x1, $y2, $x2, $range = 100, $angle = 45)
    {
        if ($x1 > $x2) {
            $tmp = $x1;
            $x1 = $x2;
            $x2 = $tmp;

            $tmp = $y1;
            $y1 = $y2;
            $y2 = $tmp;
        }

        $dx = $x2 - $x1;
        $dy = $y2 - $y1;

        // 기울기 및 radius 값을 이용해서 직선의 각도 구하기
        // 만약 dx가 0이면 기울기는 무한대이고 각도는 90도로 고정
        // dy가 0이면 기울기와 각도 모두 0
        if ($dx == 0) {
            $inc = INF;
            $deg = 90;
        } else if ($dy == 0) {
            $inc = 0;
            $deg = 0;
        } else {
            $inc = $dy / $dx;
            $deg = abs(rad2deg(atan($inc)));
        }

        // b = y - ax
        // x, y 좌표를 이용해서 1차 방정식 상수 b 구하기
        // 이 b 값이 평면상에서 구한 값이 아니라서 정확한 수치가 아닐 수 밖에 없음
        $b = $y2 - $inc * $x2;

        $old = $range;
        $break = false;
        $start = $range - ($range * ($deg / 90));
        $end = $range * 2;
        $step = 0.01;

        for ($i = $start; $i <= $end; $i += $step) {
            $c = $i * 0.00001;

            $ax = $x1 - $c;
            $ay = $inc * $ax + $b;

            $bx = $x2 + $c;
            $by = $inc * $bx + $b;

            $distance = $this->distance($y1, $x1, $ay, $ax);

            if (abs($distance - $range) < 0.5) {
                $break = true;
                break;
            } else if ($distance - $range > 2) {
                break;
            } else if ($distance - $range < -0.5) {
                $skip = round(abs($distance - $range)) / 2;
                $i += $skip;
            }

            if ($old === $range || $old > abs($distance - $range)) {
                $old = abs($distance - $range);
                $tax = $ax;
                $tay = $ay;
                $tbx = $bx;
                $tby = $by;
            }
        }
        if ($break === false) {
            $ax = $tax;
            $ay = $tay;
            $bx = $tbx;
            $by = $tby;
        }

        // 특정 좌표를 기준으로 회전변환
        $ax1 = ($ax - $x1) * cos(deg2rad(-$angle)) - ($ay - $y1) * sin(deg2rad(-$angle)) + $x1;
        $ay1 = ($ax - $x1) * sin(deg2rad(-$angle)) + ($ay - $y1) * cos(deg2rad(-$angle)) + $y1;
        $ax2 = ($ax - $x1) * cos(deg2rad($angle)) - ($ay - $y1) * sin(deg2rad($angle)) + $x1;
        $ay2 = ($ax - $x1) * sin(deg2rad($angle)) + ($ay - $y1) * cos(deg2rad($angle)) + $y1;

        $bx1 = ($bx - $x2) * cos(deg2rad($angle)) - ($by - $y2) * sin(deg2rad($angle)) + $x2;
        $by1 = ($bx - $x2) * sin(deg2rad($angle)) + ($by - $y2) * cos(deg2rad($angle)) + $y2;
        $bx2 = ($bx - $x2) * cos(deg2rad(-$angle)) - ($by - $y2) * sin(deg2rad(-$angle)) + $x2;
        $by2 = ($bx - $x2) * sin(deg2rad(-$angle)) + ($by - $y2) * cos(deg2rad(-$angle)) + $y2;

        return [
            [$ay1, $ax1],
            [$ay2, $ax2],
            [$by1, $bx1],
            [$by2, $bx2]
        ];
    }

    public function index($lat, $lng)
    {
        // 대한민국 동서남북극점
        $north = 382700000;
        $south = 330600000;
        $east = 1315200000;
        $west = 1250400000;

        $lat = $this->coord2int($lat);
        $lng = $this->coord2int($lng);

        $ns16 = ($north - $south) / 16;
        $ew16 = ($east - $west) / 16;

        $lat_index = $lng_index = null;

        for ($i = 0; $i < 16; $i += 1) {
            $lat_range = $south + ($ns16 * $i);
            $lng_range = $west + ($ew16 * $i);

            if ($lat_index === null && $lat_range > $lat) {
                $lat_index = dechex($i);
            }
            if ($lng_index === null && $lng_range > $lng) {
                $lng_index = dechex($i);
            }

            if ($lat_index !== null && $lng_index !== null) {
                break;
            }
        }

        return $lat_index.$lng_index;
    }

    public function entryCheck($lat, $lng, $square = null)
    {
        if ($square === null) {
            $square = $this->getNearSquare($lat, $lng);
        }
        $lats = $lngs = [];
        foreach($square as $vertex) {
            $lats[] = $vertex[0];
            $lngs[] = $vertex[1];
        }

        $min_lat = min($lats);
        $max_lat = max($lats);
        $min_lng = min($lngs);
        $max_lng = max($lngs);

        if ($min_lat <= $lat && $lat <= $max_lat && $min_lng <= $lng && $lng <= $max_lng) {
            return true;
        } else {
            return false;
        }
    }

    public function getNearSquare($lat, $lng, $near = null)
    {
        if ($near === null) {
            $near = $this->getNearGPS($lat, $lng);
        }

        $gp = new Gps();
        $gp->where = "WHERE group_name = '{$near['group_name']}'";
        $gpsz = $gp->getList();
        foreach($gpsz as $gps) {
            $square[] = [$gps['lat'], $gps['lng']];
        }

        if (count($square) != 4) {
            $gp->delete("WHERE group_name = '{$near['group_name']}'");
            $this->getNearSquare($lat, $lng, $near);
        }

        return $square;
    }
    public function getNearGPS($lat, $lng)
    {
        $gp = new Gps();
        $idx = $this->index($lat, $lng);
        $gp->where = "WHERE idx = '{$idx}'";
        $gpsz = $gp->getList();
        $min = INF;
        $near = null;
        foreach($gpsz as $gps) {
            $distance = $this->distance($gps['lat'], $gps['lng'], $lat, $lng);
            if ($min > $distance) {
                $min = $distance;
                $near = $gps;
                $near['distance'] = $distance;
            }
        }
        return $near;
    }
}
