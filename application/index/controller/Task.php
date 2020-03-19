<?php
namespace app\index\controller;

use app\admin\model\Room;
use app\common\controller\Frontend;
use app\common\model\City;
use app\common\model\Hotel;
use app\common\model\IHotel;
use app\common\model\Lyun;

use function fast\e;

class Task extends Frontend
{
    protected $noNeedLogin = '*';
    protected $noNeedRight = '*';
    protected $layout = '';

    public function hotels()
    {
        $ihotel = new IHotel();
        $hotels = $ihotel->hotels();
        foreach($hotels['result'] as $row) {
            unset($row['logo']);
            $hotelModel = new Hotel();

            $hotelModel->insert($row);
            echo "<br>";
        }
        
        return false;
    }

    public function rooms()
    {
        $hotelModel = new Hotel();
        $hotels = $hotelModel->select();
        foreach($hotels as $key=>$hotel) {
            $url = "http://211.159.212.161:8102/ipmsgroup/CRS/roomList?hotelGroupId=2&hotelId=".$hotel->id;
            $json = file_get_contents($url);
            $arr = json_decode($json, true);
            if($arr['resultCode'] == 0) {
                foreach($arr['result'] as $row) {
                    $data = [];
                    $data['hotel_id'] = $row['hotelId'];
                    $data['title']  = $row['roomDescript'];
                    $data['roomType'] = $row['roomType'];
                    $roomModel = new Room();
                    $roomModel->insert($data);
                    echo $roomModel->getLastSql();
                    echo "<br>";

                }

            }
        }
    }

    public function roomType()
    {
        $hotels = model('hotel')->select();

        echo "<table border=1>";
        $ihotelModel = new IHotel();
        foreach($hotels as $hotel) {
            $rs = $ihotelModel->roomList($hotel->id);
            if(isset($rs['result']) && !empty($rs['result'])) {
                foreach($rs['result'] as $row) {
                    $where = [];
                    $where['title'] = $row['roomDescript'];
                    $roomModel = new Room();
                    $room = $roomModel->where($where)->find();
                    if(!empty($room)) {
                        /**
                        $r = $room->save(['roomType'=>$row['roomType']]);
                        if($r) {
                            echo $row['roomDescript'].'同步成功<br>';
                        } else { 
                            echo $row['roomDescript'].'同步失败<br>';
                        }
                        **/
                    } else {
                        echo "<tr>";
                        echo '<td>'.$hotel->descript.'</td><td>'.$row['roomDescript'].'</td><td>'.$row['roomType'].'</td>';
                        echo "</tr>";
                    }
                    
                }
            }
        }
        echo "</table>";
    }

    public function typeList()
    {
        $ids = [
            37  => '美豪雅致杭州西湖店',
            83  => '雅致梦溪山庄',
            84  => '苏州雅致湖沁阁',
            90  => '美豪雅致新西塘越里店',
            100 => '雅致酒店西安大雁塔店'
        ];

        echo "<table border='1'>";
        foreach($ids as $key=>$value) {
            $url = "http://211.159.212.161:8102/ipmsgroup/CRS/roomList?hotelGroupId=2&hotelId={$key}";
            $data = file_get_contents($url);
            $data = json_decode($data, true);
            
            foreach($data['result'] as $val) {
                echo "<tr>";
                echo "<td>{$value}</td><td>{$val['roomDescript']}</td><td>{$val['roomType']}</td>";
                echo "</tr>";
            }
        }
        echo "</table>";
    }

    public function citys()
    {
        $hotelModel = new Hotel();
        $group = $hotelModel->field('city,cityCode')->group('city,cityCode')->select();
        foreach($group as $row) {
            $data = [];
            $data['title'] = $row->city;
            $data['cityCode'] = $row->cityCode;
            $cityModel = new City();
            $cityModel->insert($data);
        }
    }

 

    public function apply()
    {
        $model = new Lyun();
        // echo $model->registerMemberCardApply('liucunzhou', 1, '18737100406');
        $result = $model->RegisterWithCodeApply('wenyoudao', '15537911887', 1);
        var_dump($result);
    }

    public function check()
    {
        // $get = $this->request->request();
        // $model = new IHotel();
        // echo $model->edmVerifyApply('18737100406');
        $model = new Lyun();
        $result = $model->CodeSend('15537911887');
        var_dump($result);
    }

    public function reg2()
    {
        $model = new Lyun();
        // echo $model->registerMemberCardApply('liucunzhou', 1, '18737100406');
        $result = $model->Register('ddd', '18321277412', 1, 'lcz19860109');
        var_dump($result);
    }

    public function reg()
    {
        $request = $this->request->get();

        /**
        $applyId = $request['id'];
        $code = $request['code'];

        $model = new IHotel();
        echo $model->registerMemberCard($applyId, '18737100406',$code);
         * **/
        $model = new Lyun();
        $result = $model->RegisterWithCode($request['id'], '15537911887', $request['code']);
        var_dump($result);
    }

}