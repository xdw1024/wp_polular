<?php
/**
 * 任务控制器
 *
 * @date  20170320
 */
namespace app\admin\controller;

use app\admin\model\Search as SearchModel;
use app\admin\model\SearchType as SearchTypeModel;
use app\admin\model\SearchType;
use think\Request;

/**
 * 任务控制器
 *
 * @date  20170320
 */
class Type extends Common
{
    protected $search;
    public function _initialize()
    {
        parent::_initialize();
        $this->search = new SearchModel();
    }
    /**
     * 获取查询结果
     */
    public function index(){
        $keyword = input('?post.keyword')?input('post.keyword'):'';//搜索关键字
        $numPerPage =  input('?post.numPerPage')?input('post.numPerPage'):'20';//每页条数
        $currentPage =  input('?post.pageNum')?input('post.pageNum'):1;//当前页
        $searchtype = new SearchTypeModel();
        $data_count = $searchtype->where('name like "%'.$keyword.'%"')->select();
        $data_list = $searchtype->where('name like "%'.$keyword.'%"')
            ->page("$currentPage,$numPerPage")->order('id desc')->select();//分页记录集
        $totalCount = count($data_count);//总记录数
        $this->assign('totalCount',$totalCount);
        $this->assign('numPerPage',$numPerPage);
        $this->assign('currentPage',$currentPage);
        $this->assign('datalist',$data_list);
        $this->assign('keyword',$keyword);
        return $this->fetch('type/index');
    }
    /**
     * 新增/编辑 页面
     */
    public function edit()
    {
        $type_id = input('tid');
        $searchtype = new SearchTypeModel();
        $info = $searchtype->where("id='{$type_id}'")->find();
        $this->assign('info',$info);
        return  $this->fetch('type/edit');
    }
    /**
     * 信息保存
     */
    public function saveInfo()
    {
        $data = input('post.');
//        print_r($data);die();
        //$validate = $this->validate($data,'Article.saveinfo');
        $validate = true;
        $searchtype = new SearchTypeModel();
        if(true !== $validate)
        {
            $this->error($validate);
        }
        else
        {
            $type_id = input('post.id');
            if($type_id>0)//编辑
            {
                //如果帐号被修改，但与现有帐号不重复，拒绝更新
                if($data['name']!=$data['old_name'] && ($searchtype->where('name',$data['name'])->select()))
                {
                    failure('类型已存在！');
                    exit;
                }
                unset($data['old_name']);
                $result = $searchtype->where('id',$type_id)->update($data);
            }
            else//新增
            {
                $name = input('post.name');
                $typeres =$searchtype->where('name',$name)->select();
                if($typeres)
                {
                    failure('类型已存在！');
                    exit;
                }
                unset($data['old_name']);
                $data['path']="知识 (1).png";
                $result = $searchtype->insert($data);
            }

            if(false !== $result)
            {
                success('保存成功','type_list','closeCurrent');
            }
            else
            {
                failure('保存失败');
            }
        }
    }
    /**
     * 信息删除
     */
    public function removeInfo()
    {
        $searchtype = new SearchTypeModel();
        $type_id = input('tid');
        $result = $searchtype->where('id',$type_id)->delete();
        if(false !== $result)
        {
            success('删除成功','type_list','');
        }
        else
        {
            failure('删除失败！');
        }
    }

}
