<?php

namespace app\index\controller;

use app\admin\model\HotelImage;
use app\admin\model\Room;
use app\common\controller\Frontend;
use app\common\model\Hotel;
use app\common\model\IHotel;
use think\Config;
use think\Controller;
use think\Cookie;
use think\Hook;
use think\Session;
use think\Validate;

use function GuzzleHttp\Psr7\parse_query;

/**
 * 会员中心
 */
class Store extends Controller
{
    protected $layout = '';
    protected $noNeedLogin = ['room'];
    protected $noNeedRight = ['*'];
    protected $user = [];

    public function _initialize()
    {
        parent::_initialize();
        $this->user = session('user');
        if(empty($this->user)) {
            if(isset($_SERVER['HTTP_REFERER'])) {
                $http = parse_url($_SERVER['HTTP_REFERER']);
                if(isset($http['query'])) {
                    $query = parse_query($http['query']);
                } else {
                    $query = [];
                }
                $query['poplogin'] = 1;
                $url = $http['path'].'?'.http_build_query($query);
                $this->redirect($url);
            } else {
                $this->redirect('index/index');
            }
        } else {
            if($this->user['cardLevel'] == 'WXVIP') {
                $this->user['cardLevel'] = 'M';
            } else if($this->user['cardLevel'] == 'NPCEO') {
                $this->user['cardLevel'] = 'G';
            } else if($this->user['cardLevel'] == 'NCEO') {
                $this->user['cardLevel'] = 'G';
            }
            $this->assign('user', $this->user);
        }

        $hotelModel = new Hotel();
        $hotels = $hotelModel->select();
        $this->assign('hotelList', $hotels);
    }

    # 房间
    public function room()
    {
        $request = $this->request->param();
        ## 获取酒店信息
        $hotelModel = new Hotel();
        $map['id'] = $request['id'];
        $hotel = $hotelModel->where($map)->find();
        $this->assign('hotel', $hotel);

        ## 获取酒店的大图
        $imageModel = new HotelImage();
        $where['hotel_id'] = $request['id'];
        $imageObj = $imageModel->where($where)->find();
        if(!empty($imageObj)) {
            $images = explode(',', $imageObj->pics);
            $this->assign('images', $images);
        }

        ## 统计房型
        $roomModel = new Room();
        $where['hotel_id'] = $request['id'];
        $result = $roomModel->where($where)->field('roomType')->group('roomType')->select();
        $group = [];
        foreach ($result as $row) {
            if(!empty($row->roomType)) {
                $group[] = $row->roomType;
            }
        }
        $roomRows = $roomModel->where($where)->select();
        $rooms = collection($roomRows)->toArray();

        ## 获取当日房型的所有价格，普卡
        $roomTypes = implode(',', $group);
        $rateCode = "MEM-{$this->user['cardLevel']}-WEB1";
        $date = date('Y-m-d');
        $rooms = $this->getRooms($rooms, $request['id'], $rateCode, $roomTypes, $date);
        $this->assign('rooms', $rooms);

        return $this->view->fetch();
    }

    public function prebook()
    {
        $request = $this->request->param();
        $this->assign('request', $request);

        $roomModel = new Room();
        $map['id'] = $request['id'];
        $room = $roomModel->where($map)->find();

        ## 获取酒店信息
        $hotelModel = new Hotel();
        $map['id'] = $room->hotel_id;
        $hotel = $hotelModel->where($map)->find();
        $this->assign('hotel', $hotel);

        ## 获取酒店的大图
        $imageModel = new HotelImage();
        $where['hotel_id'] = $room->hotel_id;
        $imageObj = $imageModel->where($where)->find();
        if(!empty($imageObj)) {
            $images = explode(',', $imageObj->pics);
            $this->assign('images', $images);
        }

        return $this->view->fetch();
    }

    private function getRooms($rooms, $hotelId, $rateCode, $roomTypes, $date)
    {
        $ihotelModel = new IHotel();
        // $result = $ihotelModel->rateQueryEveryDay($request['id'], $rateCode, $roomTypes, $date, 1); 
        $result = $ihotelModel->rateQueryEveryDay($hotelId, $rateCode, $roomTypes, $date, 1);
        if(isset($result['queryResult']) && !empty($result['queryResult'])) {
            foreach($rooms as &$room) {
                foreach($result['queryResult'] as $value) {
                    if($room['roomType'] == $value['rmtype']) {
                        $room['price'] = $value['rate1'];
                    }
                }
            }
        }

        return $rooms;
    }
}