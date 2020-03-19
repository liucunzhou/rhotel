<?php

namespace app\index\controller;

use app\admin\model\Room;
use app\common\model\IHotel;
use app\common\model\IHotelMember;
use think\Config;
use think\Controller;
use think\Hook;
use think\Validate;

/**
 * 会员中心
 */
class User extends Controller
{
    protected $layout = '';
    // protected $noNeedLogin = ['login', 'register', 'third'];
    protected $noNeedLogin = ['login', 'register', 'getlogincode', 'getregisterapplyid', 'regsuccess'];

    public function _initialize()
    {
        parent::_initialize();
        $user = session('user');
        $action = $this->request->action();
        if(!in_array($action, $this->noNeedLogin)) {
            if(empty($user)) {
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
            }
        }
        $this->assign('user', $user);
    }

    /**
     * 空的请求
     * @param $name
     * @return mixed
     */
    public function _empty($name)
    {
        $data = Hook::listen("user_request_empty", $name);
        foreach ($data as $index => $datum) {
            $this->view->assign($datum);
        }
        return $this->view->fetch('user/' . $name);
    }

    /**
     * 会员中心
     */
    public function index()
    {
        $this->view->assign('title', __('User center'));
        return $this->view->fetch();
    }

    # 获取注册的申请ID
    public function getRegisterApplyId()
    {
        $name = $this->request->request('name', '', 'trim');
        $mobile = $this->request->request('mobile', '', 'trim');
        if(empty($name)) {
            $this->error(__('请输入用户名'), null);
            return false;
        }

        if(!is_numeric($mobile) || strlen($mobile) != 11) {
                $this->error(__('请输入正确的手机号'), null);
            return false;
        }

        $memberModel = new IHotelMember();
        $result = $memberModel->getMemberRegisterApplyId($name, $mobile);

        $this->success(__('Logged in successful'), '', $result);
    }

    /**
     * 注册会员
     */
    public function register()
    {
        $url = $this->request->request('url', '', 'trim');

        if ($this->request->isPost()) {
            $code = $this->request->post('code', '', 'trim');
            $mobile = $this->request->post('mobile', '', 'trim');
            $applyId = $this->request->post('applyId', '', 'trim');

            $ihotelModel = new IHotelMember();
            $regRes = $ihotelModel->memberRegisterWithCode($applyId, $mobile, $code);

            if ($regRes['resultCode'] == '0') {
                $this->success(__('Sign up successful'), $url ? $url : url('user/regsuccess'));
            } else {
                $this->error($regRes['resultMsg'], null);
            }
        }
        //判断来源
        $referer = $this->request->server('HTTP_REFERER');
        if (!$url && (strtolower(parse_url($referer, PHP_URL_HOST)) == strtolower($this->request->host()))
            && !preg_match("/(user\/login|user\/register|user\/logout)/i", $referer)) {
            $url = $referer;
        }
        $this->view->assign('url', $url);
        $this->view->assign('title', __('Register'));
        return $this->view->fetch();
    }

    /**
     * 会员登录
     */
    public function login()
    {
        $url = $this->request->request('url', '', 'trim');
        $referer = $this->request->server('HTTP_REFERER');
        if ($this->request->isPost()) {
            $mobile = $this->request->post('mobile', '', 'trim');
            $code = $this->request->post('code', '', 'trim');
            $verifyId = $this->request->post('verify_id', '', 'trim');

            $ihotelMember = new IHotelMember();
            $user = $ihotelMember->memberLoginWithCode($mobile, $code, $verifyId);
            if ($user['resultCode'] === 0) {
                session('user', $user);
                $this->success(__('Logged in successful'), $referer ? $referer : url('index/index'));
            } else {
                $this->error(__($user['resultMsg']));
            }
        }
        //判断来源
        if (!$url && (strtolower(parse_url($referer, PHP_URL_HOST)) == strtolower($this->request->host()))
            && !preg_match("/(user\/login|user\/register|user\/logout)/i", $referer)) {
            $url = $referer;
        }
        
        $this->view->assign('url', $url);
        $this->view->assign('title', __('Login'));
        return $this->view->fetch();
    }

    public function getLoginCode()
    {
        $mobile = $this->request->request('mobile', '', 'trim');
        if(!is_numeric($mobile) || strlen($mobile) != 11) {
             $this->error(__('请输入正确的手机号'), null);
            return false;
        }

        $memberModel = new IHotelMember();
        $result = $memberModel->getMemberLoginCode($mobile);

        $this->success(__('Logged in successful'), '', $result);
    }

    /**
     * 注销登录
     */
    public function logout()
    {
        //注销本站
        session('user', null);
        // $this->success(__('Logout successful'), url('user/index'));
        $this->redirect($_SERVER['HTTP_REFERER']);
    }

    /**
     * 个人信息
     */
    public function profile()
    {
        $user = session('user');
        $ihotelModel = new IHotel();
        $rs = $ihotelModel->findResrvList($user['cardNo']);
        if($rs['resultCode'] == '0') {
            $this->assign('list', $rs['resrvList']);
        }

        $this->view->assign('title', __('Profile'));
        return $this->view->fetch();
    }

    public function prebook()
    {
        $user = session('user');
        $request = $this->request->request();
        ## 获取房间信息
        $roomModel = new Room();
        $where = [];
        $where['id'] = $request['room_id'];
        $room = $roomModel->where($where)->find();
        
        $data['hotelId'] = $room->hotel_id;
        $data['rmtype'] = $room->roomType;
        $data['rateCode'] = "MEM-{$user['cardLevel']}-WEB1";
        $data['rateCode'] = 'RAC';
        $data['cardNo'] = $user['cardNo'];
        $data['arr'] = $request['arr'];
        $data['dep'] = $request['dep'];
        $data['rsvMan'] = $request['rsvMan'];
        $data['mobile'] = $request['mobile'];
        $data['remark'] = $request['remark'];

        $ihotel = new IHotel();
        $result  = $ihotel->book($data);

        return \json($result);
    }

    # 取消订单
    public function cancel()
    {
        $request = $this->request->request();
        if(empty($request['orderNo'])) {
            $result = [
                'resultCode'    => '-1',
                'resultMsg'     => '数据请求不对'
            ];
            return \json($result);
        }

        $user = session('user');

        $ihotel = new IHotel();
        $result  = $ihotel->cancelbook($request['orderNo'], $user['cardNo']);

        return \json($result);
    }

    /**
     * 修改密码
     */
    public function changepwd()
    {
        if ($this->request->isPost()) {
            $oldpassword = $this->request->post("oldpassword");
            $newpassword = $this->request->post("newpassword");
            $renewpassword = $this->request->post("renewpassword");
            $token = $this->request->post('__token__');
            $rule = [
                'oldpassword'   => 'require|length:6,30',
                'newpassword'   => 'require|length:6,30',
                'renewpassword' => 'require|length:6,30|confirm:newpassword',
                '__token__'     => 'token',
            ];

            $msg = [
            ];
            $data = [
                'oldpassword'   => $oldpassword,
                'newpassword'   => $newpassword,
                'renewpassword' => $renewpassword,
                '__token__'     => $token,
            ];
            $field = [
                'oldpassword'   => __('Old password'),
                'newpassword'   => __('New password'),
                'renewpassword' => __('Renew password')
            ];
            $validate = new Validate($rule, $msg, $field);
            $result = $validate->check($data);
            if (!$result) {
                $this->error(__($validate->getError()), null, ['token' => $this->request->token()]);
                return false;
            }

            $ret = $this->auth->changepwd($newpassword, $oldpassword);
            if ($ret) {
                $this->success(__('Reset password successful'), url('user/login'));
            } else {
                $this->error($this->auth->getError(), null, ['token' => $this->request->token()]);
            }
        }
        $this->view->assign('title', __('Change password'));
        return $this->view->fetch();
    }


}
