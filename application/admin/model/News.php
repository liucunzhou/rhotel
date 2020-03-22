<?php

namespace app\admin\model;

use think\Model;
use traits\model\SoftDelete;

class News extends Model
{

    use SoftDelete;

    

    // 表名
    protected $name = 'news';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = 'deletetime';

    // 追加属性
    protected $append = [
        'news_time_text'
    ];
    

    protected static function init()
    {
        self::afterInsert(function ($row) {
            $pk = $row->getPk();
            $row->getQuery()->where($pk, $row[$pk])->update(['sort' => $row[$pk]]);
        });
    }

    



    public function getNewsTimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['news_time']) ? $data['news_time'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }

    protected function setNewsTimeAttr($value)
    {
        return $value === '' ? null : ($value && !is_numeric($value) ? strtotime($value) : $value);
    }


}
