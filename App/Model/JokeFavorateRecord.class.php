<?PHP
namespace App\Model;
use \Think\Model;

/**
 * 笑话收藏记录模型类
 *
 * @author popfeng <popfeng@yeah.net>
 */
class JokeFavorateRecord extends Model
{
    protected $trueTableName = 'joke_favorate_record'; 

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
        $mTime = date('Y-m-d H:i:s');
        return $this->add(array(
            'joke_id' => $jokeId,
            'user_id' => $userId,
            'mtime' => $mTime
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
