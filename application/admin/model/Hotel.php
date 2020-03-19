<?php

namespace app\admin\model;

use think\Model;


class Hotel extends Model
{

    

    

    // 表名
    protected $name = 'hotel';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'createDatetime_text',
        'modifyDatetime_text'
    ];
    

    



    public function getCreatedatetimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['createDatetime']) ? $data['createDatetime'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }


    public function getModifydatetimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['modifyDatetime']) ? $data['modifyDatetime'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }

    protected function setCreatedatetimeAttr($value)
    {
        return $value === '' ? null : ($value && !is_numeric($value) ? strtotime($value) : $value);
    }

    protected function setModifydatetimeAttr($value)
    {
        return $value === '' ? null : ($value && !is_numeric($value) ? strtotime($value) : $value);
    }


}
