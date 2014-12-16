<?PHP
namespace App\Model;

use \Think\Model;

/**
 * 操作记录模型抽象类
 *
 * @author popfeng <popfeng@yeah.net>
 */
class AbstractActionRecord extends AbstractModel
{
    /**
     * 真实表名称
     *
     * @var string
     */
    protected $trueTableName; 

    /**
     * 本表字段名称指定
     */
    public $idFieldName      = 'id'; // 主键ID
    public $cateIdFieldName; // 业务类别ID，joke_id | comment_id
    public $userIdFiledName  = 'user_id'; // 用户ID
    public $mtimeFiledName   = 'mtime'; // 更新时间

    /**
     * joke表关联的统计字段名称
     *
     * @var string
     */
    public $jokeActionFiledName;

    /**
     * 获取单条记录数据
     *
     * @param int $id
     * @param int $userId
     * @return mixed
     */
    public function getData($id, $userId)
    {
        $condition = array(
            $this->cateIdFieldName => $id,
            $this->userIdFiledName => $userId
        );
        return $this->where($condition)->find();
    }

    /**
     * 获取多条记录数据
     *
     * @param array $ids
     * @param int $userId
     * @return mixed
     */
    public function getDatas($ids, $userId)
    {
        $condition = array(
            $this->cateIdFieldName => array('IN', $ids),
            $this->userIdFiledName => $userId
        );
        return $this->where($condition)->select();
    }

    /**
     * 添加数据
     *
     * @param int $id
     * @param int $userId
     * @return mixed
     */
    public function addData($id, $userId)
    {
        return $this->add(array(
            $this->cateIdFieldName => $id,
            $this->userIdFiledName => $userId,
            $this->mtimeFiledName  => date('Y-m-d H:i:s')
        ));
    }

    /**
     * 删除数据
     *
     * @param int $id
     * @return mixed
     */
    public function deleteData($id)
    {
        return $this->where(array(
            $this->cateIdFieldName => $id,
        ))->delete();
    }
}
