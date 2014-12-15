<?PHP
namespace App\Model;
use \Think\Model;

/**
 * 笑话模型类
 *
 * @author popfeng <popfeng@yeah.net>
 */
class Joke extends Model
{
    protected $trueTableName = 'joke'; 

    /**
     * 获取笑话详情数据
     *
     * @param int $id
     * @return mixed
     */
    public function getDetail($id)
    {
        return $this->where(
            array('id' => $id)
        )->find();
    }

    /**
     * 修改收藏数
     *
     * @param int $id
     * @param bool $isFav
     * @return mixed
     */
    public function modifyFavorateCount($id, $isFav)
    {
        $m = $this->where(
            array('id' => $id)
        );

        if ($isFav) {
            $stat = $m->setInc('favorate_count');
        } else {
            $stat = $m->setDec('favorate_count');
        }

        return $stat;
    }
}
