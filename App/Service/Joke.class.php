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
     * 根据ID获取笑话详情 
     *
     * @param int $id
     * @throws Exception
     * @return array
     */
    public function getDetail($id)
    {
        $detail = (new \App\Model\Joke)->getDetail($id);
        if (empty($detail)) {
            throw new \Exception('空的笑话详情');
        }
        return $detail;
    }

    /**
     * 是否已收藏
     *
     * @param int $id
     * @param int $userId
     * @return bool
     */
    public function isFavorate($id, $userId)
    {
        $model = new \App\Model\JokeFavorateRecord;
        $data = $model->getData($id, $userId, 1);
        return ! empty($data);
    }

    /**
     * 设置收藏
     *
     * @param int $id
     * @param int $userId
     * @param bool $isFav
     * @return bool
     */
    public function setFavorate($id, $userId, $isFav = true)
    {
        try {
            $model = new \App\Model\JokeFavorateRecord;
            $model->startTrans(); // 开始事务

            // 设置原数据
            $data = $model->getData($id, $userId);
            if ($data) {
                $model->updateData($id, $userId, (int) $isFav);
            } else {
                $model->addData($id, $userId, (int) $isFav);
            }

            // 更新统计数据
            (new \App\Model\Joke)->modifyFavorateCount($id, $isFav);

            $model->commit(); //提交事务
        } catch (\Exception $e) {
            $model->rollback(); // 事务回滚
            throw new \Exception($e->getMessage());
        }
    }
}
