<?php namespace	app\api\model;
use	think\Model;
class UanswerModel extends Model{
    protected	$pk	=	'user_answer_id';
    //	设置当前模型对应的完整数据表名称
    protected	$table	=	'course_user_answer';
    //	设置当前模型的数据库连接
    protected	$connection	=	'db_config';
}
