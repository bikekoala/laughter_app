<?PHP
namespace App\Model;
use \Think\Model;

/**
 * 笑话模型类
 *
 * @author popfeng <popfeng@yeah.net>
 */
class JokeModel extends Model
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
}
