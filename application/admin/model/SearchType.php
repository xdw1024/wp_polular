<?php
/**
 *
 * 用户模型
 * 参照dwz_thinkphp官方框架User模型编写，未用到方法已注释
 *
 */
namespace app\admin\model;

use think\Config;
use think\Model;

/**
 *
 * 查询模型
 *
 * @date  20170320
 */
class SearchType extends Model {
    public function getPathAttr($value)
    {

        return Config::get('view_replace_str.__IMGE__').$value;
    }
}