<?php

namespace app\api\model;

use think\Model;

class ClassificationModel extends Model
{
    protected $pk = 'cla_id';
    protected $table = 'course_question_classification';
}
