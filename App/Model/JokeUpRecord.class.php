<?PHP
namespace App\Model;

/**
 * 笑话赞记录模型类
 *
 * @author popfeng <popfeng@yeah.net>
 */
class JokeUpRecord extends AbstractActionRecord
{
    protected $trueTableName = 'joke_up_record'; 

    public $cateIdFieldName = 'joke_id';
    public $mtimeFiledName = 'up_time';

    public static $JOKE_ACT_FIELD_NAME = 'up_count';
}
