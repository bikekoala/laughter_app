<?PHP
namespace App\Model;

use \Think\Model;

/**
 * 用户模型类
 *
 * @author popfeng <popfeng@yeah.net>
 */
class Users extends Model
{
    protected $trueTableName = 'users'; 

    /**
     * 获取用户数据
     *
     * @param int $id
     * @return mixed
     */
    public function getData($id)
    {
        return $this->where(
            array('id' => $id)
        )->find();
    }

    /**
     * 通过用户ID获取用户信息
     *
     * @param array $ids
     * @return mixed
     */
    public function getInfoByIds($ids)
    {
        return $this->field(
            array('id', 'nickname', 'avatar')
        )->where(
            array(
                'id' => array('IN', $ids)
            )
        )->select();
    }
}
