<?php
/**
 * Admin模块配置文件
 *
 * @author  cx
 * @date  20161116
 */

/*网站根目录*/
define('WEB_ROOT', \think\Request::instance()->server('REQUEST_SCHEME') . '://' . \think\Request::instance()->server('HTTP_HOST') . '/polular');

/*上传路径文件夹*/
define('UPLOAD_FOLDER', ROOT_PATH . DS . 'uploads');

return [
    'URL_MODEL'=>1,
//    'DB_TYPE'=>'mysql',
//    'DB_HOST'=>'localhost',
//    'DB_NAME'=>'dwz_thinkphp',
//    'DB_USER'=>'root',
//    'DB_PWD'=>'root',
//    'DB_PORT'=>'3306',
//    'DB_PREFIX'=>'',

    'APP_AUTOLOAD_PATH'=>'@.TagLib',
    'SESSION_AUTO_START'=>true,

    'VAR_PAGE'=>'pageNum',

    'USER_AUTH_ON'=>true,
    'USER_AUTH_TYPE'=>1,		// 默认认证类型 1 登录认证 2 实时认证
    'USER_AUTH_KEY'=>'authId',	// 用户认证SESSION标记
    'ADMIN_AUTH_KEY'=>'administrator',
    'USER_AUTH_MODEL'=>'admin',	// 默认验证数据表模型
    'AUTH_PWD_ENCODER'=>'md5',	// 用户认证密码加密方式
    'USER_AUTH_GATEWAY'=> WEB_ROOT.'/public/index.php/Admin/Auth/Login',	// 默认认证网关
    'NOT_AUTH_MODULE'=>'',		// 默认无需认证模块
    'REQUIRE_AUTH_MODULE'=>'',		// 默认需要认证模块
    'NOT_AUTH_ACTION'=>'',		// 默认无需认证操作
    'REQUIRE_AUTH_ACTION'=>'',		// 默认需要认证操作
    'GUEST_AUTH_ON'=>false,    // 是否开启游客授权访问
    'GUEST_AUTH_ID'=>0,     // 游客的用户ID

    'DB_LIKE_FIELDS'=>'title|remark',
    'RBAC_ROLE_TABLE'=>'role',
    'RBAC_USER_TABLE'=>'role_user',
    'RBAC_ACCESS_TABLE'=>'access',
    'RBAC_NODE_TABLE'=>'node',

    // 视图输出字符串内容替换
    'view_replace_str'       => [
        '__DWZ__'          => WEB_ROOT .'/public/static/dwz/',
        '__HIGHCHARTS__'   => WEB_ROOT .'/public/static/Highcharts/',
        '__ADMIN__'        => WEB_ROOT .'/public/index.php/Admin/',
        '__ROUTE__'           => WEB_ROOT .'/public/index.php/',
        //'__CKEditor__'        => WEB_ROOT .'/public/static/ckeditor/',
        '__IMGE__'            => WEB_ROOT .'/public/static/Images/',
        '__UPLOAD_IMGE__'     => WEB_ROOT .'/uploads/Images/',
        '__PUBLIC_STATIC__'     => WEB_ROOT .'/public/static/',
        //'__FONT__'            => WEB_ROOT .'/public/static/fonts/',
    ],
    
    // 设置分页样式
//    'paginate'               => [
//        'type'      => 'app\admin\driver\AjaxPage',
//        'var_page'  => 'page',
//        'list_rows' => 10,
//    ],
    //网站名称
    'SITE_NAME'=>'法律知识科普系统',
];
