<?PHP
namespace App\Model;

/**
 * 笑话赞记录模型类
 *
 * @author popfeng <popfeng@yeah.net>
 */
class JokeUpRecord extends JokeActionRecord
{
    protected $trueTableName = 'joke_up_record'; 

    public $jokeActionFiledName = 'up_count';
}
