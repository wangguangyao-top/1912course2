<?php

namespace app\api\model\user;

use think\Model;

class User extends Model
{
    protected $pk = 'user_id';
    protected $table = 'course_user';
}
