<?php
/**
 * admin 模块公共文件
 */

/**
 * 成功返回消息(是 dwz_jui 框架表单验证中回调函数使用参数)
 * @param $message
 * @param $navTabId
 * @param $callbackType
 * @param null $forwardUrl
 */
function success($message,$navTabId,$callbackType,$forwardUrl=null)
{
    echo '{
		"statusCode":"200",
		"message":"'.$message.'",
		"navTabId":"'.$navTabId.'",
		"rel":"",
		"callbackType":"'.$callbackType.'",
		"forwardUrl":"'.$forwardUrl.'",
		"confirmMsg":""
	}';
}

/**
 * 失败返回消息 (是 dwz_jui 框架表单验证中回调函数使用参数)
 * @param $message
 */
function failure($message)
{
    echo '{
		"statusCode":"300",
		"message":"'.$message.'",
		"navTabId":"",
		"rel":"",
		"callbackType":"",
		"forwardUrl":"",
		"confirmMsg":""
	}';
}