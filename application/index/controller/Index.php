<?php

namespace app\index\controller;

use app\admin\model\HotelBackground;
use app\admin\model\HotelBanner;
use app\admin\model\Room;
use app\common\model\City;
use app\common\model\Hotel;
use fast\Date;
use think\Controller;

class Index extends Controller
{

    public function _initialize()
    {
        parent::_initialize();
        $user = session('user');
        $this->assign('user', $user);
    }

    # 首页
    public function index()
    {
        $weekArr = ['周日', '周一', '周二', '周三', '周四', '周五', '周六'];
        $weekIndex = date('w');
        $this->assign('week', $weekArr[$weekIndex]);

        ## 获取所有城市
        $cities = model('city')->select();
        $this->assign('cities', $cities);

        return $this->view->fetch();
    }

    public function search()
    {
        $cityId = $this->request->get('city', '', 'trim');
        $range = $this->request->get('range', '', 'trim');

        $cities = model('city')->select();
        $this->assign('cities', $cities);

        $cityName = '';
        foreach($cities as $row) {
            if($row->id == $cityId) {
                $cityName = $row->title;
                break;
            }
        }
        
        $map = [];
        $map['city'] = ['like', "%{$cityName}%"];
        $hotels = model('hotel')->where($map)->select();
        $this->assign('hotels', $hotels);

        return $this->view->fetch();
    }

    # 即刻体验
    public function exprience()
    {
        $cities = model('city')->select();
        $hotels = model('hotel')->select();

        $groups = [];
        foreach($cities as $city){
            $cityName = $city->title;
            $cityCode = $city->cityCode;
            foreach($hotels as $hotel) {
                $hotelCityCode = $hotel->cityCode;
                if($cityCode == $hotelCityCode) {
                    $groups[$cityName][] = $hotel->getData();
                }
            }
        }
        $this->assign('groups', $groups);

        return $this->view->fetch();
    }

    # 门店
    public function store()
    {
        $request = $this->request->param();
        ## 酒店基本信息
        $hotelModel = new Hotel();
        $where = [];
        $where['id'] = $request['id'];
        $hotel = $hotelModel->where($where)->find();
        $this->assign('hotel', $hotel);

        ## 酒店背景图片
        $backgroundModel = new HotelBackground();
        $where = [];
        $where['hotel_id'] = $request['id'];
        $background = $backgroundModel->where($where)->find();
        $backgrounds = explode(',', $background->pics);
        $this->assign('backgrounds', $backgrounds);
        if (!empty($backgrounds)) {
            $bg = "background:url('{$backgrounds[0]}')";
            $this->assign('bg', $bg);
        } else {
            $this->assign('bg', '');
        }

        ## 获取房间列表
        $roomModel = new Room();
        $where = [];
        $where['hotel_id'] = $request['id'];
        $rooms = $roomModel->where($where)->order('sort desc')->limit(5)->select();
        $this->assign('rooms', $rooms);

        ## banner
        $bannerModel = new HotelBanner();
        $where = [];
        $where['hotel_id'] = $request['id'];
        $banner = $bannerModel->where($where)->find();
        $banners = explode(',', $banner->pics);
        $this->assign('banners', $banners);

        $prebook = url('store/room', ['id'=>$request['id']]);
        $this->assign('prebook', $prebook);
        return $this->view->fetch();
    }

    ### 品牌合作
    public function cooperation()
    {
        return $this->view->fetch();
    }

    ### 品牌支持
    public function support()
    {
        return $this->view->fetch();
    }
}
