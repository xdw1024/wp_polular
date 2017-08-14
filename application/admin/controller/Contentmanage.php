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
class Contentmanage extends Common
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
        $search = new SearchModel();
        $searchtype = new SearchTypeModel();
        $keyword = input('?post.keyword')?input('post.keyword'):'';//搜索关键字
        $type = input('?post.typename')?input('post.typename'):'';
        if(!empty($type)){
            $maptyp = 'u.type_id = '.$type;
        }else{
            $maptyp = 'u.type_id like "%%"';
        }
        $numPerPage =  input('?post.numPerPage')?input('post.numPerPage'):'20';//每页条数
        $currentPage =  input('?post.pageNum')?input('post.pageNum'):1;//当前页

        $data_count = $search->alias('u')
            ->field('u.*,st.name typename')
            ->join('pol_search_type st','st.id=u.type_id','LEFT')
            ->where('title like "%'.$keyword.'%"')->where($maptyp)->select();
        $data_list = $search->alias('u')
                ->field('u.*,st.name typename')
                ->join('pol_search_type st','st.id=u.type_id','LEFT')
            ->where('title like "%'.$keyword.'%"')->where($maptyp)
            ->page("$currentPage,$numPerPage")->order('id desc')->select();//分页记录集
        $typelist=$searchtype->select();
        $totalCount = count($data_count);//总记录数
        $this->assign('totalCount',$totalCount);
        $this->assign('numPerPage',$numPerPage);
        $this->assign('currentPage',$currentPage);
        $this->assign('datalist',$data_list);
        $this->assign('typelist',$typelist);
        $this->assign('keyword',$keyword);
        $this->assign('typename',$type);
        return $this->fetch('contentmanage/index');
    }
    /**
     * 新增/编辑 页面
     */
    public function edit()
    {
        $contentid = input('tid');
        $searchtype = new SearchTypeModel();
        $search = new SearchModel();
        $info = $search->alias('u')
            ->field('u.*,st.name typename')
            ->join('pol_search_type st','st.id=u.type_id','LEFT')
            ->where("u.id='{$contentid}'")->find();
        $typelist=$searchtype->select();
        $this->assign('info',$info);
        $this->assign('type_list',$typelist);
        return  $this->fetch('contentmanage/edit');
    }
    /**
     * 信息保存
     */
    public function saveInfo()
    {
        $data = input('post.');
        //$validate = $this->validate($data,'Article.saveinfo');
        $validate = true;
        $search = new SearchModel();
        if(true !== $validate)
        {
            $this->error($validate);
        }
        else
        {
            $contentid = input('post.id');
            if($contentid>0)//编辑
            {
                //如果帐号被修改，但与现有帐号不重复，拒绝更新
                if($data['title']!=$data['old_title'] && ($search->where('title',$data['title'])->select()))
                {
                    failure('该标题内容已存在！');
                    exit;
                }
                unset($data['old_title']);
                $result = $search->where('id',$contentid)->update($data);
            }
            else//新增
            {
                if($search->where('title',$data['title'])->select())
                {
                    failure('该标题内容已存在！');
                    exit;
                }
                unset($data['old_title']);
                $result = $search->insert($data);
            }

            if(false !== $result)
            {
                success('保存成功','contentmanagge_list','closeCurrent');
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
        $search = new SearchModel();
        $contentid = input('tid');
        $result = $search->where('id',$contentid)->delete();
        if(false !== $result)
        {
            success('删除成功','contentmanagge_list','');
        }
        else
        {
            failure('删除失败！');
        }
    }
}
