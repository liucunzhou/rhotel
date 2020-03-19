<?php

namespace app\admin\model;

use think\Model;


class Room extends Model
{

    

    

    // 表名
    protected $name = 'room';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = false;

    public function hotel()
    {
        return $this->belongsTo('Hotel', 'hotel_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }
}
