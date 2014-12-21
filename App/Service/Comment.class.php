<?PHP
namespace App\Service;

use \App\Model\CommentUpRecord;

/**
 * 评论逻辑服务
 *
 * @author popfeng <popfeng@yeah.net>
 */
class Comment extends AbstractService
{
    protected $model;

    /**
     * construct
     *
     * @return void
     */
    public function __construct($jokeId, $userId)
    {
        parent::__construct($jokeId, $userId);

        $this->model = new \App\Model\Comment;
    }

    /**
     * 获取他人回复我的评论列表
     *
     * @param array $mineComments
     * @return array
     */
    public function getRepliedMine($mineComments)
    {
        $commentIds = array();
        foreach ($mineComments as $item) {
            $commentIds[] = $item['id'];
        }
        $comments = $this->model->getRepliedList($this->jokeId, $commentIds);

        return $this->_process($comments);
    }

    /**
     * 获取自己的评论列表
     *
     * @return array
     */
    public function getMine()
    {
        $comments =  $this->model->getListByJokeidAndUserid(
            $this->jokeId,
            $this->userId
        );

        return $this->_process($comments);
    }

    /**
     * 获取神评论列表
     *
     * @param int $limit
     * @return array
     */
    public function getSuper($limit = 5)
    {
        $comments = $this->model->getListOrderByUpcount($this->jokeId, 10, $limit);

        return $this->_process($comments);
    }

    /**
     * 获取最新评论列表
     *
     * @param int $start
     * @param int $limit
     * @return array
     */
    public function getLastest($start = 0, $limit = 10)
    {
        $comments = $this->model->getListByJokeid(
            $this->jokeId,
            $start,
            $limit
        );

        return $this->_process($comments);
    }

    /**
     * 设置赞的操作
     *
     * @param int $id
     * @param bool $isAct
     * @return bool
     */
    public function setUp($id, $isAct = true)
    {
        try {
            $model = new CommentUpRecord;
            $model->startTrans(); // 开始事务

            // 设置原子数据
            $data = $model->getData($id, $this->userId);
            if ($data) {
                $model->deleteData($id);
            } else {
                $model->addData($id, $this->userId, (int) $isAct);
            }

            // 更新统计数据
            $this->model->modifyActionCount(
                $id,
                $isAct,
                $model::$jokeActionFiledName
            );

            $model->commit(); //提交事务
        } catch (\Exception $e) {
            $model->rollback(); // 事务回滚
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * 添加评论、回复
     *
     * @param string $content
     * @param int $replyCmtId
     * @return void
     */
    public function addComment($content, $replyCmtId = null)
    {
        try {
            $this->model->startTrans(); // 开始事务

            $this->model->addData(
                $content,
                $this->userId,
                $this->jokeId,
                $replyCmtId
            );
            (new \App\Model\Joke)->modifyActionCount(
                $this->jokeId,
                true,
                \App\Model\JokeCmtRecord::$JOKE_ACT_FIELD_NAME
            );

            $this->model->commit(); //提交事务
        } catch (\Exception $e) {
            $this->model->rollback(); // 事务回滚
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * 处理评论数据
     *
     * @param array $comments
     * @return array
     */
    private function _process($comments)
    {
        // 格式化日期
        foreach ($comments as &$item) {
            $item['create_time'] = date(
                'm-d H:i',
                strtotime($item['create_time'])
            );
        }

        // 合并是否点赞状态
        $this->_mergeActionStatus($comments);

        // 合并用户信息
        return $this->_mergeUserInfo($comments);
    }

    /**
     * 合并动作状态
     *
     * @param array $comments
     * @return void
     */
    private function _mergeActionStatus(&$comments)
    {
        $ids = array();
        foreach ($comments as $item) {
            $ids[] = $item['id'];
        }
        $result = $this->_isAction(
            new \App\Model\CommentUpRecord,
            $ids
        );

        foreach ($comments as &$item) {
            $item['is_up'] = (int) $result[$item['id']];
        }
    }

    /**
     * 合并用户信息到评论记录
     *
     * @param array $comments
     * @return array
     */
    private function _mergeUserInfo($comments)
    {
        if ( ! empty($comments)) {
            $userIds = array();
            foreach ($comments as $item) {
                $userIds[$item['user_id']] = $item['user_id'];
            }

            $userInfo = (array) (new \App\Model\Users)->getDataByIds($userIds);
            foreach ($userInfo as $user) {
                foreach ($comments as &$comment) {
                    if ($user['id'] === $comment['user_id']) {
                        $comment['user_nickname'] = $user['nickname'];
                        $comment['user_avatar']   =
                            static::fillImageUrl($user['avatar']);
                    }
                }
            }
        } else {
            $comments = array();
        }

        return $comments;
    }
}
