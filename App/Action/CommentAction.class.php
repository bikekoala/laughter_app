<?PHP
namespace App\Action;

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
        $jokeId = (int) $_GET['joke_id'];
        $userId = (int) $_GET['user_id'];
        $start  = (int) $_GET['start'];
        $limit  = ((int) $_GET['limit']) ? : 10;
        if ( ! $jokeId || ! $userId) {
            $this->outputJSON('Invalid params.', false);
        }

        // service
        try {
            $commentService  = new \App\Service\Comment;
            $lastestComments = $commentService->getLastest($jokeId, $userId, $start, $limit);
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
}
