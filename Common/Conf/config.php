<?php
/**
 * 模块配置文件
 * wangbaoqing@imooly.com
 * 2014-07-15
 */
require_once('db.config.php');
require_once('host.config.php');

return array(
	//默认语言
	'DEFAULT_LANG'  => 'zh-cn',

	'SHOW_ERROR_MSG'        =>  true,    // 显示错误信息
	
	/**
	 * 数据库配置信息
	 * 支持多数据库配置
	 */
	'DB_CONFIG' => $dbconfig,

	//mongodb配置信息
	'MONGO'     => $mongo,

	//HOST
	'HOST' => $HOST,

	//SESSION配置信息
	'SESSION_TYPE'       => '',
	'SESSION_PREFIX'     => 'inquzhou',
	'VAR_SESSION_ID'     => 'sessionid',
	'SESSION_OPTIONS'    => array(
		'name'   => 'inquzhou',
		'expire' => 7200 //session默认过期时间 2小时=7200秒
	),
);