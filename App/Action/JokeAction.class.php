<?PHP
namespace App\Action;

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
        // get params
        $jokeId = (int) $_GET['joke_id'];
        $userTid = trim($_GET['user_tid']);
        $userId = $this->tidToId($userTid);
        if ( ! $jokeId) {
            $this->error('无效的ID', C('PORTAL_URL'));
        }

        // service
        try {
            $joke = (new \App\Service\Joke)->getDetail($jokeId, $userId);
            $user = (new \App\Service\User)->getDetail($joke['user_id']);

            $repliedMineComments = array();
            $mineComments = array();
            $isFavorate = false;
            if ($userId) {
                $commentService  = new \App\Service\Comment;
                $mineComments = $commentService->getMine($jokeId, $userId);
                $repliedMineComments = $commentService->getRepliedMine(
                    $jokeId,
                    $mineComments
                );
            }
            $superComments = $commentService->getSuper($jokeId);
            $lastestComments = $commentService->getLastest($jokeId, $userId);
        } catch (\Exception $e) {
            $this->error($e->getMessage(), C('PORTAL_URL'));
        }

        // vendor
        $this->assign('user_tid', $userTid);
        $this->assign('joke', $joke);
        $this->assign('joke_user', $user);
        $this->assign('comment_replied_mine', $repliedMineComments);
        $this->assign('comment_mine', $mineComments);
        $this->assign('comment_super', $superComments);
        $this->assign('comment_lastest', $lastestComments);
        $this->display();
    }

    /**
     * 收藏操作
     *
     * @return void
     */
    public function favorate()
    {
        // get params
        $jokeId = (int) $_REQUEST['joke_id'];
        $userTid = trim($_REQUEST['user_tid']);
        $isFav = (bool) $_REQUEST['is_fav'];
        $userId = $this->tidToId($userTid);
        if ( ! $jokeId || ! $userId) {
            $this->outputJSON('Invalid params.', false);
        }

        // process
        try {
            (new \App\Service\Joke)->setFavorate($jokeId, $userId, $isFav);
        } catch (\Exception $e) {
            $this->outputJSON('操作失败!', false);
        }
        $this->outputJSON('操作成功~');
    }
}
