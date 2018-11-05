<?php
/**
 * 数据库函数文件
 */
// 防止恶意调用
if (! defined ( 'IN_TG' )) {
	exit ( 'Access Defined!' );
}

/**
 * _connect() 连接MYSQL数据库  构造函数
 * 
 * @access public
 * @return void
 */
function _connect() {
	// global 表示全局变量的意思，意图是将此变量在函数外部也能访问
    //DB_HOST  主机地址   DB_USER  数据库用户  DB_PWD 数据库密码
	global $_conn;
	if (! $_conn = @mysql_connect ( DB_HOST, DB_USER, DB_PWD )) {
		exit ( '数据库连接失败' );
	}
}

/**
 * _select_db选择一款数据库
 * 
 * @return void
 */
function _select_db() {
	if (! mysql_select_db ( DB_NAME )) {
		exit ( '找不到指定的数据库' );
	}
}

/**
 * _set_names 设置数据库编码
 */
function _set_names() {
	if (! mysql_query ( 'SET NAMES UTF8' )) {
		exit ( '字符集错误' );
	}
}

/**
 *_query  mysql 执行语句
 * @param
 *        	$_sql
 */
function _query($_sql) {
	// var_dump($sql);
	if (! $_result = mysql_query ( $_sql )) {
		// var_dump($_result);
		exit ( 'SQL执行失败' . mysql_error () );
	}
	return $_result;
}

/**
 * _fetch_array只能获取指定数据集一条数据组
 * 
 * @param
 *        	$_sql
 */
function _fetch_array($_sql) {
	return mysql_fetch_array ( _query ( $_sql ), MYSQL_ASSOC );
}

/**
 * _fetch_array_list可以返回指定数据集的所有数据
 * 
 * @param
 *        	$_result
 */
function _fetch_array_list($_result) {
	return mysql_fetch_array ( $_result, MYSQL_ASSOC );
}

/** _num_rows  返回指定数据集的所有数据条数  整型
 * @param $_result
 * @return int
 */
function _num_rows($_result) {
	return mysql_num_rows ( $_result );
}

/**
 * _affected_rows表示影响到的记录数
 */
function _affected_rows() {
	return mysql_affected_rows ();
}

/**
 * _free_result 函数释放结果内存。
 * 
 * @param
 *        	$_result
 */
function _free_result($_result) {
	mysql_free_result ( $_result );
}

/**
 * _insert_id  函数返回上一步 INSERT 操作产生的 ID
 */
function _insert_id() {
	return mysql_insert_id ();
}

/**
 *
 * @param
 *        	$_sql
 * @param
 *        	$_info
 */
function _is_repeat($_sql, $_info) {
	if (_fetch_array ( $_sql )) {
		_alert_back ( $_info );
	}
}

/**
 * 关闭数据库
 */
function _close() {
	if (! mysql_close ()) {
		exit ( '关闭异常' );
	}
}

?>