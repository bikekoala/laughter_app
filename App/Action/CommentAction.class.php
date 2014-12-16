<?PHP
namespace App\Action;

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
        if ( ! $this->jokeId || ! $this->userId) {
            $this->outputJSON('Invalid params.', false);
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
        $isAct = (int) $_REQUEST['is_act'];
        if ( ! $this->jokeId || ! $this->userId || ! $cmtId) {
            $this->outputJSON('Invalid params.', false);
        }

        // process
        try {
            // call action
            (new Comment($this->jokeId, $this->userId))->setUp($cmtId, $isAct);

            // push message
            if (1 === $isAct) {
                $this->_push(Push::OP_UP_CMT);
            }
        } catch (\Exception $e) {
            $this->outputJSON('操作失败!', false);
        }
        $this->outputJSON('操作成功~');
    }

}
