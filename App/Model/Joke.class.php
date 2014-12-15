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
     * 修改操作数
     *
     * @param int $id
     * @param bool $isAct
     * @param string $fieldName
     * @return mixed
     */
    public function modifyActionCount($id, $isAct, $fieldName)
    {
        $m = $this->where(
            array('id' => $id)
        );

        if ($isAct) {
            $stat = $m->setInc($fieldName);
        } else {
            $stat = $m->setDec($fieldName);
        }

        return $stat;
    }
}
