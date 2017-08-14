<?php
/**
 * 认证控制器
 * 包含登陆，登出、调用RBAC认证，验证码功能
 */
namespace app\admin\controller;


use app\admin\model\Admin;
use \think\Session;
use \think\Config;

/**
 *
 * 认证控制器
 */
class Auth extends \think\Controller
{

    /**
     *
     * 用户登录页面，已登陆用户跳转dwz主界面
     */
    public function login()
    {
        if(! \think\Session::get(Config::get('USER_AUTH_KEY')))
        {
            //未登陆
            return $this->fetch();
        }
        else
        {
            //已登陆
            $this->redirect('Manage/index');
        }
    }

    /**
     *
     * 用户登出
     */
    public function logout()
    {
        $a = Session::get(Config::get('USER_AUTH_KEY'));
        var_dump($a);
        if(Session::get(Config::get('USER_AUTH_KEY')))
        {
            Session::delete(Config::get('USER_AUTH_KEY'));
            Session::destroy();
            //$this->success('登出成功！');
            $this->redirect('Auth/login');
        }
        else
        {
//            $this->error('已经登出！');
//            $this->success('已经登出','login','');
            $this->redirect('Auth/login');
        }
    }

    /**
     *
     * 登录检测
     */
    public function checkLogin()
    {
        if(empty($_POST['account']))
        {
            $this->error('帐号错误！');
        }
        elseif (empty($_POST['password']))
        {
            $this->error('密码必须！');
        }
        elseif (empty($_POST['verify']))
        {
            $this->error('验证码必须！');
        }
        //生成认证条件
        $map            =   array();
        // 支持使用绑定帐号登录
        $map['account']	= $_POST['account'];
        $map["status"]	=	array('gt',0);
        if(Session::get('verify') != md5($_POST['verify']))
        {
            $this->error('验证码错误！');
        }
        import ( 'ORG.Util.RBAC' );
        $authInfo = \RBAC::authenticate($map);
        //使用用户名、密码和状态的方式进行认证
        if(null === $authInfo)
        {
            $this->error('帐号不存在或已禁用！');
        }
        else
        {
            if($authInfo['password'] != md5($_POST['password']))
            {
                $this->error('密码错误！');
            }
            Session::set(Config::get('USER_AUTH_KEY'),$authInfo['id']);
//            Session::set('email',$authInfo['email']);
            Session::set('loginUserName',$authInfo['nickname']);
            //Session::set('lastLoginTime',$authInfo['last_login_time']);
            //Session::set('login_count',$authInfo['login_count']);
            //Session::set('type_id',$authInfo['type_id']);
            //if($authInfo['account']=='admin')
            $admins = \think\Db::view('admin')->column('account');
            if(in_array($authInfo['account'],$admins))
            {
                Session::set('administrator',true);
            }
            //保存登录信息
//            $Admin	=	new Admin;
//            $ip		=	get_client_ip();
//            $time	=	time();
//            $data = array();
//            $data['last_login_time']	=	$time;
//            $data['login_count']	=	array('exp','login_count+1');
//            $data['last_login_ip']	=	$ip;

//            $Admin->save($data,['id' => $authInfo['id']]);
            // 缓存访问权限
            \RBAC::saveAccessList();
            //$this->success('登录成功！');
            $this->redirect('Manage/index');
        }
    }

    /**
     *
     * 验证码显示
     *
     * @author  cx
     * @date  20161116
     */
    public function verify()
    {
        $type	 =	 isset($_GET['type'])?$_GET['type']:'gif';
        import('ORG.Util.Image');
        \Image::buildImageVerify(4,1,$type);
    }

}