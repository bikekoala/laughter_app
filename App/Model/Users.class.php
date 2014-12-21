<?PHP
namespace App\Model;

use \Think\Model;

/**
 * 用户模型类
 *
 * @author popfeng <popfeng@yeah.net>
 */
class Users extends AbstractModel
{
    protected $trueTableName = 'users'; 

    /**
     * 通过TOKEN获取数据
     *
     * @param string $token
     * @return mixed
     */
    public function getDataByToken($token)
    {
        return $this->where(
            array('token' => $token)
        )->find();
    }
}
