<?PHP
namespace App\Service;

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
     * @return array
     */
    public function getDetail()
    {
        $data = $this->getData($this->jokeId);

        $data['is_up'] = (int) $this->_isAction(
            new \App\Model\JokeUpRecord,
            $this->jokeId
        );
        $data['is_favorate'] = (int) $this->_isAction(
            new \App\Model\JokeFavorateRecord,
            $this->jokeId
        );
        return $data;
    }

    /**
     * 获取笑话数据
     *
     * @throws Exception
     * @return array
     */
    public function getData()
    {
        $data = (new \App\Model\Joke)->getData($this->jokeId);
        if (empty($data)) {
            throw new \Exception('空的笑话详情');
        } else {
            $data['img_url'] = static::fillImageUrl($data['img_url']);
        }
        return $data;
    }

    /**
     * 设置动作的操作
     *
     * @param int $actionType
     * @param bool $isAct
     * @return bool
     */
    public function setAction($actionType, $isAct = true)
    {
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
            $data = $model->getData($this->jokeId, $this->userId);
            if ($data) {
                $model->deleteData($this->jokeId);
            } else {
                $model->addData($this->jokeId, $this->userId, (int) $isAct);
            }

            // 更新统计数据
            (new \App\Model\Joke)->modifyActionCount(
                $this->jokeId,
                $isAct,
                $model::$JOKE_ACT_FIELD_NAME
            );

            $model->commit(); //提交事务
        } catch (\Exception $e) {
            $model->rollback(); // 事务回滚
            throw new \Exception($e->getMessage());
        }
    }
}
