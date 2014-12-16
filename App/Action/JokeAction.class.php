<?PHP
namespace App\Action;

use \App\Service\User;
use \App\Service\Joke;
use \App\Service\Comment;
use \App\Service\JPush\Push;

/**
 * 笑话控制器
 *
 * @author popfeng <popfeng@yeah.net>
 */
class JokeAction extends AbstractAction
{
    /**
     * 笑话详情页面
     *
     * @return void
     */
    public function detail()
    {
        // validate
        if ( ! $this->jokeId) {
            $this->error('无效的笑话ID', C('PORTAL_URL'));
        }

        // call service
        try {
            $joke = (new Joke)->getDetail($this->jokeId, $this->userId);
            $jokeUser = (new User)->getData($joke['user_id']);

            $repliedMineComments = array();
            $mineComments = array();
            $isFavorate = false;
            if ($this->userId) {
                $commentService  = new Comment;
                $mineComments = $commentService->getMine($this->jokeId, $this->userId);
                $repliedMineComments = $commentService->getRepliedMine(
                    $this->jokeId,
                    $mineComments
                );
            }
            $superComments = $commentService->getSuper($this->jokeId);
            $lastestComments = $commentService->getLastest($this->jokeId, $this->userId);
        } catch (\Exception $e) {
            $this->error($e->getMessage(), C('PORTAL_URL'));
        }

        // vendor
        $this->assign('user_tid', $this->userTid);
        $this->assign('joke', $joke);
        $this->assign('joke_user', $jokeUser);
        $this->assign('comment_replied_mine', $repliedMineComments);
        $this->assign('comment_mine', $mineComments);
        $this->assign('comment_super', $superComments);
        $this->assign('comment_lastest', $lastestComments);
        $this->display();
    }

    /**
     * 赞操作
     *
     * @return void
     */
    public function up()
    {
        // get params & validate
        $isAct = (bool) $_REQUEST['is_act'];
        if ( ! $this->jokeId || ! $this->userId) {
            $this->outputJSON('Invalid params.', false);
        }

        // process
        try {
            // call action
            (new Joke)->setAction(
                Joke::ACT_UP,
                $this->jokeId,
                $this->userId, 
                $isAct
            );

            // push message
            if (1 === $isAct) {
                $this->_push(Push::OP_UP_JOKE);
            }
        } catch (\Exception $e) {
            $this->outputJSON('操作失败!', false);
        }
        $this->outputJSON('操作成功~');
    }

    /**
     * 收藏操作
     *
     * @return void
     */
    public function favorate()
    {
        // get params & validate
        $isAct = (bool) $_REQUEST['is_act'];
        if ( ! $this->jokeId || ! $this->userId) {
            $this->outputJSON('Invalid params.', false);
        }

        // process
        try {
            // call action
            (new Joke)->setAction(
                Joke::ACT_FAV,
                $this->jokeId,
                $this->userId, 
                $isAct
            );
        } catch (\Exception $e) {
            $this->outputJSON('操作失败!', false);
        }
        $this->outputJSON('操作成功~');
    }
}
