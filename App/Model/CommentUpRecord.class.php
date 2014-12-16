<?PHP
namespace App\Model;

/**
 * 评论赞记录模型类
 *
 * @author popfeng <popfeng@yeah.net>
 */
class CommentUpRecord extends AbstractActionRecord
{
    protected $trueTableName = 'comment_up_record'; 

    public $cateIdFieldName = 'comment_id';
    public $mtimeFiledName = 'up_time';

    public $jokeActionFiledName = 'up_count';
}
