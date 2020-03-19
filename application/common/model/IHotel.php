<?php
namespace app\common\model;

use fast\Http;

class IHotel{
    private $baseUrl = 'http://211.159.212.161:8102';
    private $hotelGroupId = 2;
    private $hotelGroupCode = 'SHMHJDG';

    # 查询酒店信息
    public function hotels()
    {
        $url = $this->baseUrl.'/ipmsgroup/CRS/hotels?hotelGroupCode='.$this->hotelGroupCode;
        $result = file_get_contents($url);

        return json_decode($result, true);
    }

    # 房型列表
    # http://211.159.212.161:8102/ipmsgroup/CRS/roomList?hotelGroupId=2&hotelId=118
    public function roomList($hotelId=118)
    {
        $url = $this->baseUrl.'/ipmsgroup/CRS/roomList?hotelGroupId='.$this->hotelGroupId.'&hotelId='.$hotelId;
        $result = file_get_contents($url);

        return json_decode($result, true);
    }

    # 查询房价
    public function queryHotelList($date,$dayCount=1,$cityCode='HZ',$salesChannel='WEBCHAT')
    {
        $params['hotelGroupId'] = $this->hotelGroupId;
        $params['date'] = $date;
        $params['dayCount'] = $dayCount;
        $params['cityCode'] = $cityCode;
        $params['salesChannel'] = $salesChannel;
        $query = http_build_query($params);

        $url = $this->baseUrl.'/ipmsgroup/CRS/queryHotelList?'.$query;
        $result = file_get_contents($url);

        return json_decode($result, true);
    }

    # 查询每日房价
    public function rateQueryEveryDay($hotelId, $rateCode, $rmType='BOK', $date, $dayCount)
    {
        $params['hotelId'] = $hotelId;
        $params['hotelGroupId'] = $this->hotelGroupId;
        $params['rateCode'] = $rateCode;
        $params['rmType'] = $rmType;
        $params['date'] = $date;
        $params['dayCount'] = $dayCount;
        $query = http_build_query($params);

        $url = $this->baseUrl.'/ipmsgroup/CRS/rateQueryEveryDay?'.$query;
        $result = file_get_contents($url);

        return json_decode($result, true);
    }

    # 查询房量
    # http://211.159.212.161:8102/ipmsgroup/CRS/listRoomAvail?hotelGroupId=2&hotelId=118&arr=2019-10-22&dep=2019-10-25&rmtype=SDT
    public function listRoomAvail($hotelId, $arr, $dep, $rmtype='SDT')
    {
        $params['hotelGroupId'] = $this->hotelGroupId;
        $params['hotelId'] = $hotelId;
        $params['arr'] = $arr;
        $params['dep'] = $dep;
        $params['rmtype'] = $rmtype;
        $query = http_build_query($params);

        $url = $this->baseUrl.'/ipmsgroup/CRS/listRoomAvail?'.$query;
        $result = file_get_contents($url);

        return json_decode($result, true);
    }

    # 查询可用房
    # http://211.159.212.161:8102/ipmsgroup/CRS/listRoomsSaleable?hotelGroupId=2&hotelId=118&arr=2019-10-22&dep=2019-10-26&rmtype=BOK
    public function listRoomsSaleable($hotelId, $arr, $dep, $rmtype='BOK')
    {
        $params['hotelGroupId'] = $this->hotelGroupId;
        $params['hotelId'] = $hotelId;
        $params['arr'] = $arr;
        $params['dep'] = $dep;
        $params['rmtype'] = $rmtype;
        $query = http_build_query($params);

        $url = $this->baseUrl.'/ipmsgroup/CRS/listRoomAvail?'.$query;
        $result = file_get_contents($url);

        return json_decode($result, true);
    }

    # 预定
    # http://211.159.212.161:8102/ipmsgroup/CRS/book?hotelId=118&hotelGroupId=2&src=GN&arr=2019-10-30 12:00:00&dep=2019-11-03 14:00:00&rmtype=BOK&rateCode=RAC&rmNum=1&adult=1&rsvMan=刘存州&sex=1&mobile=18321277411&idType=&idNo=&email=&cardType=MEHOODV01&cardNo=0001661660&remark=test&packages=BF1&rooms=&market=&everyDayRate=&channel=WEB
    public function book($data)
    {
        $params['hotelId'] = $data['hotelId'];
        $params['hotelGroupId'] = $this->hotelGroupId;
        $params['src'] = 'GN';
        $params['arr'] = $data['arr'];
        $params['dep'] = $data['dep'];
        $params['rmtype'] = $data['rmtype'];
        $params['rateCode'] = $data['rateCode'];
        $params['rmNum'] = 1;
        $params['adult'] = 1;
        $params['rsvMan'] = $data['rsvMan'];
        $params['sex'] = 1;
        $params['mobile'] = $data['mobile'];
        $params['idType'] =  '';
        $params['idNo'] = '';
        $params['email'] = '';
        $params['cardType'] = 'MEHOODV01';
        $params['cardNo'] = $data['cardNo'];
        $params['remark'] = $data['remark'];
        $params['packages'] = '';
        $params['rooms'] = '';
        $params['market'] = '';
        $params['everyDayRate'] =  '';
        $params['channel'] = 'WEB';

        $query = http_build_query($params);
        $url = $this->baseUrl.'/ipmsgroup/CRS/book?'.$query;
        $result = file_get_contents($url);

        return json_decode($result, true);
    }

    # 查询订单
    # http://211.159.212.161:8102/ipmsgroup/CRS/findResrvList?cardNo=8100306069&firstResult&pageSize=1&arr=2016-01-01&dep=2019-10-01&crsNo&hotelGroupId=2
    public function findResrvList($cardNo='', $arr='', $dep='', $pageSize=1)
    {
        $params['cardNo'] = $cardNo;
        $params['firstResult'] = '';
        $params['pageSize'] = $pageSize;
        $params['arr'] = $arr;
        $params['dep'] = $dep;
        $params['crsNo'] = '';
        $params['hotelGroupId'] = $this->hotelGroupId;

        $query = http_build_query($params);
        $url = $this->baseUrl.'/ipmsgroup/CRS/findResrvList?'.$query;
        $result = file_get_contents($url);

        return json_decode($result, true);
    }

   # 取消订单
    # http://211.159.212.161:8102/ipmsgroup/CRS/cancelbook?hotelGroupId=2&crsNo=W1910250180&cardNo=8100306069
    public function cancelbook($crsNo, $cardNo)
    {
        $params['hotelGroupId'] = $this->hotelGroupId;
        $params['crsNo'] = $crsNo;
        $params['cardNo'] = $cardNo;

        $query = http_build_query($params);
        $url = $this->baseUrl.'/ipmsgroup/CRS/cancelbook?'.$query;
        $result = file_get_contents($url);

        return json_decode($result, true);
    }

    private function post($url, $data)
    {
        $query = http_build_query($data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Apache-HttpClient/4.1.1 (java 1.5)');
        curl_setopt($ch, CURLOPT_HEADER, ['Content-Type:application/x-www-form-urlencoded']);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}