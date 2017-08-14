<?php
/**
 * 任务控制器
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
class Search extends \think\Controller
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
        //获取单位信息
        $type = input('?get.type')?input('get.type'):'';
        $keyword = input('?get.keyword')?input('get.keyword'):'';//搜索关键字
        $search = new SearchModel();
        $searchtype = new SearchTypeModel();
        if(empty($type)){
            $datalist = $search->where('title like "%'.$keyword.'%"')->order('id desc');
            $typename =null;
        }
        else{
            $datalist = $search->where('title like "%'.$keyword.'%"')
                ->where('type_id ='.$type.'')->order('id desc');
            $typedata = $searchtype->where('id ='.$type.'')->find();
            $typename=$typedata['name'];
        }
        $datalist = $datalist->paginate(10, false, ['type' => 'app\admin\driver\AjaxPage', 'var_page' => 'page',
                'query' => Request::instance()->param()]);

        // 获取分页显示
        $page = $datalist->render();
        // 模板变量赋值
        $this->assign('page', $page);
        //将单位信息填入显示页面
        $this->assign('datalist',$datalist);
        $this->assign('keyword',$keyword);
        $this->assign('type',$type);
        $this->assign('typename',$typename);
        return $this->fetch('search/index');
    }
    /**
     * 查看选定内容页面
     */
    public function showcontent(){
        $search = new SearchModel();
        $contentid =  input('?get.contentid')?input('get.contentid'):'';
        $content = $search->where('id ='.$contentid.'')->find();
        $this->assign('content',$content);
        return $this->fetch('search/searchresult');
    }
    /**
     * 新增内容页面模板
     */
    public function editcontent(){
        $search = new SearchModel();
        $searchtype = new SearchTypeModel();
        $type = $searchtype->select();
        $this->assign('type',$type);
        return $this->fetch('search/edit');
    }
    /**
     * 更新内容页面模板
     */
    public function changeedit(){
        $search = new SearchModel();
        $searchtype = new SearchTypeModel();
        $contentid = input('?get.contentid')?input('get.contentid'):'';
       // var_dump($contentid);die;
        $content = $search->where('id ='.$contentid.'')->find();
        $type = $searchtype->select();
        $this->assign('content',$content);
        $this->assign('contentid',$contentid);
        $this->assign('type',$type);
        return $this->fetch('search/change');
    }
    /**
     *删除内容
     */
    public function delcontent(){
        $search = new SearchModel();
        $contentid = input('?get.contentid')?input('get.contentid'):'';
        $result = $search->where('id',$contentid)->delete();
        if(false !== $result)
        {

//            $this->success('删除成功','managecontent','');
            $this->redirect('Search/managecontent');
        }
    }
    /**
     *添加删除内容
     */
    public function insertcontent(){
        $search = new SearchModel();
        $data = input('post.');
        //var_dump($data);die;
        $result =  $search ->insert($data);
        if(false !== $result)
        {
            $this->success('添加成功','managecontent','');
        }
    }
    /**
     *更新内容
     */
    public function changecontent(){
        $search = new SearchModel();
        $data = input('post.');
        $contentid = input('post.id');
        //var_dump($contentid);die;
        $result = $search->where('id',$contentid)->update($data);
        if(false !== $result)
        {
            $this->success('更新成功','Search/managecontent','');
        }
    }
    /**
     *内容管理主页
     */
    public function managecontent(){
        $type = input('?get.type')?input('get.type'):'';
        $keyword = input('?get.keyword')?input('get.keyword'):'';//搜索关键字
        $search = new SearchModel();
        if(empty($type)){
            $datalist = $search->where('title like "%'.$keyword.'%"')->order('id desc');
        }
        else{
            $datalist = $search->where('title like "%'.$keyword.'%"')
                ->where('type_id ='.$type.'')->order('id desc');
        }
        $datalist = $datalist->paginate(10, false, ['type' => 'app\admin\driver\AjaxPage', 'var_page' => 'page',
            'query' => Request::instance()->param()]);

        // 获取分页显示
        $page = $datalist->render();
        // 模板变量赋值
        $this->assign('page', $page);
        //将单位信息填入显示页面
        $this->assign('datalist',$datalist);
        $this->assign('keyword',$keyword);
        $this->assign('type',$type);
        return $this->fetch('search/manage');
    }


}
