<?PHP
namespace App\Model;

use \Think\Model;

/**
 * 笑话收藏记录模型类
 *
 * @author popfeng <popfeng@yeah.net>
 */
class JokeFavorateRecord extends JokeActionRecord
{
    protected $trueTableName = 'joke_favorate_record'; 

    public $jokeActionFiledName = 'favorate_count';
}
