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
     * @param bool|null $isFav
     * @return mixed
     */
    public function getData($jokeId, $userId, $isFav = null)
    {
        $condition = array(
            'joke_id' => $jokeId,
            'user_id' => $userId
        );
        if (null !== $isFav) {
            $condition['is_favorate'] = $isFav;
        }
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
    public function addData($jokeId, $userId, $isFav = 1)
    {
        $mTime = date('Y-m-d H:i:s');
        return $this->add(array(
            'joke_id' => $jokeId,
            'user_id' => $userId,
            'is_favorate' => $isFav,
            'mtime' => $mTime
        ));
    }

    /**
     * 更新数据
     *
     * @param int $jokeId
     * @param int $userId
     * @param int $isFav
     * @return mixed
     */
    public function updateData($jokeId, $userId, $isFav)
    {
        $mTime = date('Y-m-d H:i:s');
        return $this->where(array(
            'joke_id' => $jokeId,
            'user_id' => $userId,
        ))->save(array(
            'is_favorate' => $isFav,
            'mtime' => $mTime
        ));
    }
}
