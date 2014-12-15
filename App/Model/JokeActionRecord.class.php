<?PHP
namespace App\Model;
use \Think\Model;

/**
 * 笑话操作记录模型抽象类
 *
 * @author popfeng <popfeng@yeah.net>
 */
class JokeActionRecord extends Model
{
    /**
     * 真实表名称
     *
     * @var string
     */
    protected $trueTableName; 

    /**
     * joke表关联的统计字段名称
     *
     * @var string
     */
    public $jokeActionFiledName;

    /**
     * 获取记录数据
     *
     * @param int $jokeId
     * @param int $userId
     * @return mixed
     */
    public function getData($jokeId, $userId)
    {
        $condition = array(
            'joke_id' => $jokeId,
            'user_id' => $userId
        );
        return $this->where($condition)->find();
    }

    /**
     * 添加数据
     *
     * @param int $jokeId
     * @param int $userId
     * @param int $isFav
     * @return mixed
     */
    public function addData($jokeId, $userId)
    {
        return $this->add(array(
            'joke_id' => $jokeId,
            'user_id' => $userId,
            'up_time' => date('Y-m-d H:i:s')
        ));
    }

    /**
     * 删除数据
     *
     * @param int $jokeId
     * @return mixed
     */
    public function deleteData($jokeId)
    {
        return $this->where(array(
            'joke_id' => $jokeId,
        ))->delete();
    }
}
