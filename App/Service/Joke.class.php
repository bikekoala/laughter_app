<?PHP
namespace App\Service;
use App\Service\AbstractService;

/**
 * 笑话逻辑服务
 *
 * @author popfeng <popfeng@yeah.net>
 */
class Joke extends AbstractService
{
    /**
     * 操作常亮
     */
    const ACT_UP = 0;
    const ACT_FAV = 1;

    /**
     * 根据ID获取笑话详情 
     *
     * @param int $id
     * @param int $userId
     * @throws Exception
     * @return array
     */
    public function getDetail($id, $userId)
    {
        $detail = (new \App\Model\Joke)->getDetail($id);
        if (empty($detail)) {
            throw new \Exception('空的笑话详情');
        }

        $detail['is_up'] = (int) $this->_isAction(
            new \App\Model\JokeUpRecord,
            $id,
            $userId
        );
        $detail['is_favorate'] = (int) $this->_isAction(
            new \App\Model\JokeFavorateRecord,
            $id,
            $userId
        );
        return $detail;
    }

    /**
     * 设置动作的操作
     *
     * @param int $model
     * @param int $id
     * @param int $userId
     * @param bool $isAct
     * @return bool
     */
    public function setAction(
        $actionType,
        $id,
        $userId,
        $isAct = true
    ) {
        try {
            switch($actionType) {
                case self::ACT_UP :
                    $model = new \App\Model\JokeUpRecord;
                    break;
                case self::ACT_FAV :
                    $model = new \App\Model\JokeFavorateRecord;
            }
            $model->startTrans(); // 开始事务

            // 设置原子数据
            $data = $model->getData($id, $userId);
            if ($data) {
                $model->deleteData($id);
            } else {
                $model->addData($id, $userId, (int) $isAct);
            }

            // 更新统计数据
            (new \App\Model\Joke)->modifyActionCount(
                $id,
                $isAct,
                $model->jokeActionFiledName
            );

            $model->commit(); //提交事务
        } catch (\Exception $e) {
            $model->rollback(); // 事务回滚
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * 是否已操作
     *
     * @param object $model
     * @param int $id
     * @param int $userId
     * @return bool
     */
    private function _isAction(
        \App\Model\JokeActionRecord $model,
        $id,
        $userId
    ) {
        if ($userId) {
            $data = $model->getData($id, $userId);
            return ! empty($data);
        } else {
            return false;
        }
    }
}
