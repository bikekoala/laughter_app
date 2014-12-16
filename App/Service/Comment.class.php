<?PHP
namespace App\Service;

use App\Service\AbstractService;

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
    public function __construct()
    {
        $this->model = new \App\Model\Comment;
    }

    /**
     * 获取他人回复我的评论列表
     *
     * @param int $jokeId
     * @param array $mineComments
     * @return array
     */
    public function getRepliedMine($jokeId, $mineComments)
    {
        $commentIds = array();
        foreach ($mineComments as $item) {
            $commentIds[] = $item['id'];
        }
        $comments = $this->model->getRepliedList($jokeId, $commentIds);

        return $this->_process($comments);
    }

    /**
     * 获取自己的评论列表
     *
     * @param int $jokeId
     * @param int $userId
     * @return array
     */
    public function getMine($jokeId, $userId)
    {
        $comments =  $this->model->getListByJokeidAndUserid($jokeId, $userId);

        return $this->_process($comments);
    }

    /**
     * 获取神评论列表
     *
     * @param int $jokeId
     * @param int $limit
     * @return array
     */
    public function getSuper($jokeId, $limit = 5)
    {
        $comments = $this->model->getListOrderByUpcount($jokeId, $limit);

        return $this->_process($comments);
    }

    /**
     * 获取最新评论列表
     *
     * @param int $jokeId
     * @param int $userId
     * @param int $start
     * @param int $limit
     * @return array
     */
    public function getLastest($jokeId, $userId, $start = 0, $limit = 10)
    {
        $comments = $this->model->
            getListByJokeidButUserid($jokeId, $userId, $start, $limit);

        return $this->_process($comments);
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

        // 合并用户信息
        return $this->_mergeUserInfo($comments);
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

            $userInfo = (array) (new \App\Model\Users)
                ->getInfoByIds($userIds);
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
