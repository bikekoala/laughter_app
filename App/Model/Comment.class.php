<?PHP
namespace App\Model;

use \Think\Model;

/**
 * 评论模型类
 *
 * @author popfeng <popfeng@yeah.net>
 */
class Comment extends AbstractModel
{
    protected $trueTableName = 'comment'; 

    /**
     * 获取被回复的评论列表
     *
     * @param int $jokeId
     * @param array $commentIds
     * @return mixed
     */
    public function getRepliedList($jokeId, $commentIds)
    {
        return $this->field(
            array(
                'id',
                'user_id',
                'content',
                'up_count',
                'create_time'
            )
        )->where(
            array(
                'joke_id' => $jokeId,
                'is_closed' => 0,
                'reply_comment_id' => array(
                    'IN',
                    $commentIds
                )
            )
        )->order('create_time DESC')->select();
    }

    /**
     * 通过用户ID和笑话ID获取评论列表
     *
     * @param int $jokeId
     * @param int $userId
     * @return mixed
     */
    public function getListByJokeidAndUserid($jokeId, $userId)
    {
        return $this->field(
            array(
                'id',
                'user_id',
                'content',
                'up_count',
                'create_time'
            )
        )->where(
            array(
                'user_id'   => $userId,
                'joke_id'   => $jokeId,
                'is_closed' => 0
            )
        )->order('create_time DESC')->select();
    }

    /**
     * 通过笑话ID获取评论列表
     *
     * @param int $userId
     * @param int $start
     * @param int $limit
     * @return mixed
     */
    public function getListByJokeid($jokeId, $start, $limit)
    {
        return $this->field(
            array(
                'id',
                'user_id',
                'content',
                'up_count',
                'create_time'
            )
        )->where(
            array(
                'joke_id'   => $jokeId,
                'is_closed' => 0
            )
        )->order('create_time DESC')->limit($start, $limit)->select();
    }

    /**
     * 通过笑话ID获取评论列表，并排序指定用户记录
     *
     * @param int $userId
     * @param int $start
     * @param int $limit
     * @return mixed
     */
    public function getListByJokeidButUserid($jokeId, $userId, $start, $limit)
    {
        return $this->field(
            array(
                'id',
                'user_id',
                'content',
                'up_count',
                'create_time'
            )
        )->where(
            array(
                'user_id'   => array(
                    'NEQ', $userId
                ),
                'joke_id'   => $jokeId,
                'is_closed' => 0
            )
        )->order('create_time DESC')->limit($start, $limit)->select();
    }

    /**
     * 获取经过点赞数排序后的评论列表
     *
     * @param int $jokeId
     * @param int $countLimit
     * @param int $recordLimit
     * @return mixed
     */
    public function getListOrderByUpcount($jokeId, $countLimit, $recordLimit)
    {
        return $this->field(
            array(
                'id',
                'user_id',
                'content',
                'up_count',
                'create_time'
            )
        )->where(
            array(
                'joke_id'   => $jokeId,
                'is_closed' => 0,
                'up_count' => array(
                    'EGT',
                    $countLimit
                )
            )
        )->order('up_count DESC')->limit($recordLimit)->select();
    }

    /**
     * 添加数据
     *
     * @param string $content
     * @param int $userId
     * @param int $jokeId
     * @param int $replyCmtId
     * @return mixed
     */
    public function addData($content, $userId, $jokeId, $replyCmtId = null)
    {
        $data = array(
            'user_id' => $userId,
            'joke_id' => $jokeId,
            'content' => $content,
            'create_time' => date('Y-m-d H:i:s')
        );
        if ($replyCmtId) {
            $data['reply_comment_id'] = $replyCmtId;
        }
        return $this->add($data);
    }
}
