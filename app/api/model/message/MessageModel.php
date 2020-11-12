<?php

namespace app\api\model\message;

use think\Model;

class MessageModel extends Model
{
    protected $pk = 'message_id';
    protected $table = 'course_message';
}
