<?php

namespace app\admin\controller;

use app\admin\model\HotelBackground;
use app\admin\model\HotelBanner;
use app\admin\model\HotelImage;
use app\common\controller\Backend;
use think\Db;

/**
 * 
 *
 * @icon fa fa-hotel
 */
class Hotel extends Backend
{
    
    /**
     * Hotel模型对象
     * @var \app\admin\model\Hotel
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\Hotel;

    }
    
    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */
    
    /**
     * 查看
     */
    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model
                ->where($where)
                ->order($sort, $order)
                ->count();

            $list = $this->model
                ->where($where)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();

            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

    public function images($ids = null)
    {
        $hotelImage = new HotelImage();
        $where = [];
        $where['hotel_id'] = $ids;
        $row = $hotelImage->where($where)->find();
        $adminIds = $this->getDataLimitAdminIds();
        if (is_array($adminIds)) {
            if (!in_array($row[$this->dataLimitField], $adminIds)) {
                $this->error(__('You have no permission'));
            }
        }

        if ($this->request->isPost()) {

            $params = $this->request->post("row/a");
            if ($params) {
                $params = $this->preExcludeFields($params);
                $params['hotel_id'] = $ids;
                $result = false;
                Db::startTrans();
                try {
                    if(!empty($row)) {
                        $result = $row->allowField(true)->save($params);
                    } else {
                        $result = $hotelImage->allowField(true)->save($params);
                    }
                    Db::commit();
                } catch (PDOException $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                } catch (Exception $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                }
                if ($result !== false) {
                    $this->success();
                } else {
                    $this->error(__('No rows were updated'));
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        $this->view->assign("row", $row);
        return $this->view->fetch();
    }

    /**
     * 背景图片
     */
    public function background($ids = null)
    {
        $model = new HotelBackground();
        $where = [];
        $where['hotel_id'] = $ids;
        $row = $model->where($where)->find();
        $adminIds = $this->getDataLimitAdminIds();
        if (is_array($adminIds)) {
            if (!in_array($row[$this->dataLimitField], $adminIds)) {
                $this->error(__('You have no permission'));
            }
        }

        if ($this->request->isPost()) {

            $params = $this->request->post("row/a");
            if ($params) {
                $params = $this->preExcludeFields($params);
                $params['hotel_id'] = $ids;
                $result = false;
                Db::startTrans();
                try {
                    if(!empty($row)) {
                        $result = $row->allowField(true)->save($params);
                    } else {
                        $result = $model->allowField(true)->save($params);
                    }
                    Db::commit();
                } catch (PDOException $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                } catch (Exception $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                }
                if ($result !== false) {
                    $this->success();
                } else {
                    $this->error(__('No rows were updated'));
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        $this->view->assign("row", $row);
        return $this->view->fetch();
    }

    /**
     * Banner图
     */
    public function banner($ids = null)
    {
        $model = new HotelBanner();
        $where = [];
        $where['hotel_id'] = $ids;
        $row = $model->where($where)->find();
        $adminIds = $this->getDataLimitAdminIds();
        if (is_array($adminIds)) {
            if (!in_array($row[$this->dataLimitField], $adminIds)) {
                $this->error(__('You have no permission'));
            }
        }

        if ($this->request->isPost()) {

            $params = $this->request->post("row/a");
            if ($params) {
                $params = $this->preExcludeFields($params);
                $params['hotel_id'] = $ids;
                $result = false;
                Db::startTrans();
                try {
                    if(!empty($row)) {
                        $result = $row->allowField(true)->save($params);
                    } else {
                        $result = $model->allowField(true)->save($params);
                    }
                    Db::commit();
                } catch (PDOException $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                } catch (Exception $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                }
                if ($result !== false) {
                    $this->success();
                } else {
                    $this->error(__('No rows were updated'));
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        $this->view->assign("row", $row);
        return $this->view->fetch();
    }
}
