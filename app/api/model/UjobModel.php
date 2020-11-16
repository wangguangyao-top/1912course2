<?php namespace	app\api\model;
use	think\Model;
class UjobModel extends Model{
    protected	$pk	=	'user_job_id';
    //	设置当前模型对应的完整数据表名称
    protected	$table	=	'course_user_job';
    //	设置当前模型的数据库连接
    protected	$connection	=	'db_config';
}
