<?php
/**
 * 控制器通用父类，其他类可以继承
 * 包含RBAC权限认证
 * 参照dwz_thinkphp官方框架Common控制器编写，未用到方法已注释
 */

namespace app\admin\controller;

use \think\Request;
use \think\Session;
use \think\Config;

/**
 *
 * 控制器通用父类
 *
 */
class Common extends \think\Controller
{

    /**
     * 控制器初始化方法,在该控制器其他方法调用之前首先执行
     * 调用RBAC权限认证
     */
    function _initialize()
    {
        $request = Request::instance();
        // 用户权限检查
        if (Config::get('USER_AUTH_ON')&& !in_array($request->controller(),explode(',',Config::get('NOT_AUTH_MODULE'))))
        {
            import('ORG.Util.RBAC');
            if (! \RBAC::AccessDecision ($request))
            {
                //检查认证识别号
                if(! Session::get(Config::get('USER_AUTH_KEY')))
                {
                    //跳转到认证网关
                    $this->redirect(Config::get( 'USER_AUTH_GATEWAY' ));
                }
                // 没有权限 抛出错误(目前用不到)
                if (Config::get( 'RBAC_ERROR_PAGE' ))
                {
                    // 定义权限错误页面
                    //redirect ( Config::get( 'RBAC_ERROR_PAGE' ) );
                    $this->redirect(Config::get( 'USER_AUTH_GATEWAY' ));
                }
                else
                {
                    //游客模式(目前用不到)
                    if (Config::get( 'GUEST_AUTH_ON' ))
                    {
                        $this->redirect(Config::get( 'USER_AUTH_GATEWAY' ));
                    }
                    // 提示错误信息
                    $this->error ( Config::get( '_VALID_ACCESS_' ) );
                }
            }
        }
    }

}