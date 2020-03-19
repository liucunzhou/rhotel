<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 2020/3/19
 * Time: 9:31
 */
namespace app\index\controller;


use think\Controller;

class News extends Controller
{

    public function index()
    {
        return $this->view->fetch();
    }
}