<?PHP
namespace App\Action;

use \App\Service\Joke;
use \App\Service\Comment;
use \App\Service\JPush\Push;

/**
 * 评论控制器
 *
 * @author popfeng <popfeng@yeah.net>
 */
class CommentAction extends AbstractAction
{
    /**
     * 最新评论列表接口
     *
     * @return void
     */
    public function lastest()
    {
        // get params
        $start  = (int) $_GET['start'];
        $limit  = ((int) $_GET['limit']) ? : 10;
        if ( ! $this->userId) {
            $this->outputJSON('无效的用户ID~', false);
        }
        if ( ! $this->jokeId) {
            $this->outputJSON('无效的笑话ID~', false);
        }

        // service
        try {
            $lastestComments = (new Comment($this->jokeId, $this->userId))
                ->getLastest($start,$limit);
        } catch (\Exception $e) {
            $this->outputJSON('oops.', false);
        }

        // output
        $this->outputJSON(
            array(
                'start' => $start + count($lastestComments),
                'list'  => $lastestComments
            )
        );
    }

    /**
     * 赞操作
     *
     * @return void
     */
    public function up()
    {
        // get params & validate
        $cmtId = (int) $_REQUEST['comment_id'];
        $isAct = (bool) $_REQUEST['is_act'];
        if ( ! $this->userTid || ! $this->userId) {
            $this->outputJSON('请先登录~', false);
        }
        if ( ! $this->jokeId) {
            $this->outputJSON('无效的笑话ID~', false);
        }
        if ( ! $cmtId) {
            $this->outputJSON('无效的评论ID~', false);
        }

        // process
        try {
            // call action
            (new Comment($this->jokeId, $this->userId))->setUp($cmtId, $isAct);

            // push message
            if ($isAct) {
                $this->_push(Push::OP_UP_CMT, $cmtId);
            }
        } catch (\Exception $e) {
            $this->outputJSON('操作失败!', false);
        }
        $this->outputJSON('操作成功~');
    }

    /**
     * 添加评论
     *
     * @return void
     */
    public function add()
    {
        // get params & validate
        $comment = trim($_REQUEST['comment']);
        if ( ! $this->userTid || ! $this->userId) {
            $this->outputJSON('请先登录~', false);
        }
        if ( ! $this->jokeId) {
            $this->outputJSON('无效的笑话ID~', false);
        }
        if ( ! $comment) {
            $this->outputJSON('无效的评论内容~', false);
        }

        // process
        try {
            $id = (new Comment($this->jokeId, $this->userId))->addComment($comment);

            // push message
            $this->_push(Push::OP_RE_JOKE, $comment);
        } catch (\Exception $e) {
            $this->outputJSON('操作失败!', false);
        }
        $this->outputJSON($id);
    }

    /**
     * 添加回复
     *
     * @return void
     */
    public function reply()
    {
        // get params & validate
        $replyCmtId = (int) $_REQUEST['comment_id'];
        $comment = trim($_REQUEST['comment']);
        if ( ! $this->userTid || ! $this->userId) {
            $this->outputJSON('请先登录~', false);
        }
        if ( ! $this->jokeId) {
            $this->outputJSON('无效的笑话ID~', false);
        }
        if ( ! $replyCmtId) {
            $this->outputJSON('无效的回复评论ID~', false);
        }
        if ( ! $comment) {
            $this->outputJSON('无效的回复内容~', false);
        }

        // process
        try {
            $commentService = new Comment($this->jokeId, $this->userId);
            $replyCommentData = $commentService->getData($replyCmtId);
            if ($this->userId == $replyCommentData['user_id']) {
                $this->outputJSON('试试回复其他小伙伴的评论吧~', false);
            }
            $id = $commentService->addComment(
                $comment,
                $replyCmtId
            );

            // push message
            $this->_push(Push::OP_RE_CMT, $comment); // 推送Joke作者
            $this->_push(Push::OP_RE_CMT, $comment, $replyCmtId); // 推送评论作者
        } catch (\Exception $e) {
            $this->outputJSON('操作失败!', false);
        }
        $this->outputJSON($id);
    }
}
