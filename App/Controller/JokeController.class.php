<?PHP
namespace App\Controller;
use Think\Controller;

/**
 * 笑话控制器
 *
 * @author popfeng <popfeng@yeah.net>
 */
class JokeController extends Controller
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
        $userId = (int) $_GET['user_id'];
        if (0 === $jokeId) {
            $this->error('无效的ID', C('PORTAL_URL'));
        }

        // service
        try {
            $joke = (new \App\Service\JokeService)->getDetail($jokeId);
            $user = (new \App\Service\UserService)->getDetail($joke['user_id']);

            $repliedMineComments = array();
            $mineComments = array();
            $superComments = array();
            $lastestComments = array();
            if (0 !== $userId) {
                $commentService  = new \App\Service\CommentService;
                $mineComments = $commentService->getMine($jokeId, $userId);
                $repliedMineComments = $commentService->getRepliedMine(
                    $jokeId,
                    $mineComments
                );
                $superComments = $commentService->getSuper($jokeId);
                $lastestComments = $commentService->getLastest($jokeId, $userId);
            }
        } catch (\Exception $e) {
            $this->error($e->getMessage(), C('PORTAL_URL'));
        }

        // vendor
        $this->assign('joke', $joke);
        $this->assign('joke_user', $user);
        $this->assign('comment_replied_mine', $repliedMineComments);
        $this->assign('comment_mine', $mineComments);
        $this->assign('comment_super', $superComments);
        $this->assign('comment_lastest', $lastestComments);
        $this->assign('user_id', $userId);
        $this->display();
    }

    /**
     * 空操作
     *
     * @return void
     */
    public function _empty()
    {
        $msg = '我有一头小毛驴呀我从来也不骑～';
        $url = C('PORTAL_URL');
        $this->error($msg, $url);
    }
}
