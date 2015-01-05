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
            $joke = (new Joke($this->jokeId, $this->userId))->getDetail();
            $jokeUser = (new User)->getData($joke['user_id']);

            $commentService  = new Comment($this->jokeId, $this->userId);
            $repliedMineComments = array();
            $mineComments = array();
            if ($this->userId) {
                $mineComments = $commentService->getMine();
                $repliedMineComments = $commentService->getRepliedMine(
                    $mineComments
                );
            }
            $superComments = $commentService->getSuper();
            $lastestComments = $commentService->getLastest();
        } catch (\Exception $e) {
            $this->error($e->getMessage(), C('PORTAL_URL'));
        }

        // vendor
        $this->assign('is_login', (int) ! empty($this->userId));
        $this->assign('user_tid', $this->userTid);
        $this->assign('user_data', $this->userData);
        $this->assign('joke', $joke);
        $this->assign('joke_user', $jokeUser);
        $this->assign('comment_replied_mine', $repliedMineComments);
        $this->assign('comment_mine', $mineComments);
        $this->assign('comment_super', $superComments);
        $this->assign('comment_lastest', $lastestComments);
        $this->assign('static_ver', C('STATIC_VER'));
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
        if ( ! $this->userTid || ! $this->userId) {
            $this->outputJSON('请先登录~', false);
        }
        if ( ! $this->jokeId) {
            $this->outputJSON('无效的笑话ID~', false);
        }

        // process
        try {
            // call action
            (new Joke($this->jokeId, $this->userId))->setAction(
                Joke::ACT_UP,
                $isAct
            );

            // push message
            if ($isAct) {
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
        $this->outputJSON('操作成功~');
        // get params & validate
        /*
        $isAct = (bool) $_REQUEST['is_act'];
        if ( ! $this->userTid || ! $this->userId) {
            $this->outputJSON('请先登录~', false);
        }
        if ( ! $this->jokeId) {
            $this->outputJSON('无效的笑话ID~', false);
        }

        // process
        try {
            // call action
            (new Joke($this->jokeId, $this->userId))->modifyActionCount(
                \App\Model\JokeFavorateRecord::$JOKE_ACT_FIELD_NAME,
                true
            );
        } catch (\Exception $e) {
            $this->outputJSON('操作失败!', false);
        }
        $this->outputJSON('操作成功~');
        */
    }
}
