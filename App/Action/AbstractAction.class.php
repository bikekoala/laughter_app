<?PHP
namespace App\Action;

use Think\Action;
use \App\Service\User;
use \App\Service\Joke;
use \App\Service\JPush\Push;

/**
 * 公共控制器
 *
 * @author popfeng <popfeng@yeah.net>
 */
class AbstractAction extends Action
{
    /**
     * 笑话ID
     *
     * @var string
     */
    public $jokeId;

    /**
     * 操作用户Token Id
     *
     * @var string
     */
    public $userTid;

    /**
     * 操作用户ID
     *
     * @var string
     */
    public $userId;

    /**
     * 构造函数
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        if (isset($_REQUEST['joke_id'])) {
            $this->jokeId = (int) $_REQUEST['joke_id'];
        }
        if (isset($_REQUEST['user_id'])) {
            $this->userId = (int) $_REQUEST['user_id'];
        }
        if (isset($_REQUEST['user_tid'])) {
            $this->userTid = trim($_REQUEST['user_tid']);
            if ($this->userTid && ! $this->userId) {
                $this->userId = User::decryptUserId($this->userTid);
            }
        }
    }

    /**
     * 封装推送服务
     *
     * @param int $opType
     * @param string $content
     * @return void
     */
    protected function _push($opType, $content = '')
    {
        // get associate info
        $joke = (new Joke)->getData($this->jokeId);
        $user = (new User)->getData($this->userId);

        // push message
        if ( ! empty($joke) && ! empty($user)) {
            $pushService = new Push($opType);
            $pushService->setJokeId($joke['id']);
            $pushService->setJokeUserId($joke['user_id']);
            $pushService->setJokeUserName('讲个笑话吧');
            $pushService->setOpUserId($user['id']);
            $pushService->setOpUserName($user['nickname']);
            $pushService->setOpUserAvatar($user['avatar']);
            $pushService->setOpUserToken($user['token']);
            $pushService->setSourceContent($content);

            $pushService->fire();
        }
    }

    /**
     * 输出JSON信息
     *
     * @param mixed $data
     * @param bool $status
     * @return string
     */
    public function outputJSON($data = null, $status = true)
    {
        $this->ajaxReturn(
            array(
                'status' => $status,
                'data'   => $data
            )
        );
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
