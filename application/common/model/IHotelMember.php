<?php

namespace app\common\model;

use fast\Http;

class IHotelMember
{
    private $baseUrl = 'http://211.159.212.161:8101/ipmsmember/membercard/';
    private $hotelGroupId = 2;
    private $hotelGroupCode = 'SHMHJDG';

    # http://211.159.212.161:8101/ipmsmember/membercard/memberLogin?hotelGroupId=2&loginId=18321277412&loginPassword=lcz19860109
    public function memberLogin($loginId, $loginPassword)
    {
        $url = $this->baseUrl . 'memberLogin';
        $params['hotelGroupId'] = $this->hotelGroupId;
        $params['loginId'] = $loginId;
        $params['loginPassword'] = $loginPassword;
        $result = $this->post($url, $params);
        return json_decode($result, true);
    }

    public function registerMemberCardWithOutVerify($name, $mobile, $password)
    {
        $url = $this->baseUrl . 'registerMemberCardWithOutVerify';
        $params = [
            'hotelGroupId'  => $this->hotelGroupId,
            'name'          => $name,
            'sex'           => 1,
            'mobile'        => $mobile,
            'email'         => '',
            'idType'        => '',
            'idNo'          => '',
            'password'      => $password,
        ];

        $result = $this->post($url, $params);
        return json_decode($result, true);
    }

    public function getMemberLoginCode($mobile)
    {
        $url = $this->baseUrl . 'edmVerifyApply';
        $params = [
            'hotelGroupId' => $this->hotelGroupId,
            'mobile' => $mobile,
            'cardType' => '',
            'cardLevel' => '',
        ];

        $json = $this->post($url, $params);

        return json_decode($json, true);
    }

    public function memberLoginWithCode($mobile, $code, $verifyId)
    {

        $timer = time();
        // mobileVerifyApply
        // $sign = md5("memberLoginByMobileCode*HYJTG*" . $timer);
        $sign = md5("memberLoginByMobileCode*{$this->hotelGroupCode}*" . $timer);
        $url = $this->baseUrl . 'memberLoginByMobileCode';
        $params = [
            'hotelGroupId' => $this->hotelGroupId,
            'mobile' => $mobile,
            'verifyCode' => $code,
            'verifyId' => $verifyId,
            'timestamp' => $timer,
            'sign' => $sign,
        ];

        $json = $this->post($url, $params);
        return json_decode($json, true);
    }

    # 获取验证码注册申请ID
    public function getMemberRegisterApplyId($name, $mobile)
    {
        $url = $this->baseUrl . 'registerMemberCardApply';
        $params = [
            'hotelGroupId' => $this->hotelGroupId,
            'name' => $name,
            'sex' => 1,
            'mobile' => $mobile,
            'email' => '',
            'idType' => '',
            'idNo' => '',
            'verifyType' => '0',
            'verifyHost' => '',
        ];
        $json = $this->post($url, $params);
        return json_decode($json, true);
    }

    # 使用验证码
    public function memberRegisterWithCode($applyId, $mobile, $code)
    {
        $url = $this->baseUrl . 'registerMemberCard';
        $params = [
            'hotelGroupId' => $this->hotelGroupId,
            'applyId' => $applyId,
            'mobileOrEmail' => $mobile,
            'verifyCode' => $code,
            'cardSrc' => '7',
        ];

        $json = $this->post($url, $params);
        return json_decode($json, true);
    }

    private function post($url, $data)
    {
        $query = http_build_query($data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        // curl_setopt($ch, CURLOPT_USERAGENT, 'Apache-HttpClient/4.1.1 (java 1.5)');
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/x-www-form-urlencoded']);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $result = curl_exec($ch);
        // var_dump($result);
        curl_close($ch);

        return $result;
    }
}
