<?php
namespace app\admin\controller;

use app\admin\model\SearchType as SearchTypeModel;

class Index extends \think\Controller
{
    public function index()
    {
        $pagenum=input('?get.pagenum')?input('get.pagenum'):'0';
        $type = new SearchTypeModel();
        $num = $pagenum * 9;
        $typelist = $type->limit("$num,9")->order('id asc')->select();
        $list = $type->select();
        $count=count($list);
        $maxpagenum=ceil($count/9);
        $this->assign('typelist',$typelist);
        $this->assign('pagenum',$pagenum);
        $this->assign('maxpagenum',$maxpagenum);
        return $this->fetch();
    }
    public function gettype(){
        $pagenum=input('?get.pagenum')?input('get.pagenum'):'0';
        $type = new SearchTypeModel();
        $num = $pagenum * 9;
        $typelist = $type->limit("$num,9")->order('id asc')->select();
        return $typelist;
    }
}
