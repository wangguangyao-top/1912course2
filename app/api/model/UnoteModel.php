<?php namespace	app\api\model;
use	think\Model;
class UnoteModel extends Model{
    protected	$pk	=	'user_note_id';
    //	设置当前模型对应的完整数据表名称
    protected	$table	=	'course_user_note';
    //	设置当前模型的数据库连接
    protected	$connection	=	'db_config';
}
