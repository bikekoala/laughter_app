<?PHP
namespace App\Action;

use Think\Action;
use \App\Service\User;
use \App\Service\Joke;
use \App\Service\Comment;
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
     * 用户数据
     *
     * @var mixed
     */
    public $userData;

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
                try {
                    $token = User::decryptUserToken($this->userTid);
                    $userData = (new \App\Service\User)->getDataByToken($token);
                    if ( ! empty($userData)) {
                        $this->userId = $userData['id'];
                        $this->userData = $userData;
                    }
                } catch (\Exception $e) {
                }
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
    protected function _push($opType, ...$params)
    {
        // get associate info
        $joke = (new Joke($this->jokeId))->getData();
        $user = (new User)->getData($this->userId);

        // push message
        if ( ! empty($joke) &&
             ! empty($user) &&
             $this->userId != $joke['user_id']
        ) {
            $pushService = new Push($opType);
            $pushService->setJokeId($joke['id']);
            $pushService->setJokeUserId($joke['user_id']);
            $pushService->setJokeUserName('讲个笑话吧');
            $pushService->setOpUserId($user['id']);
            $pushService->setOpUserName($user['nickname']);
            $pushService->setOpUserAvatar($user['avatar']);
            if (Push::OP_RE_JOKE === $opType) {
                $pushService->setOpContent($params[0]);
                $pushService->setSourceContent($joke['content']);
            }
            if (Push::OP_RE_CMT === $opType) {
                $cmt = (new Comment)->getData($params[1]);
                $pushService->setOpContent($params[0]);
                $pushService->setSourceContent($cmt['content']);
            }

            $pushService->fire();
        }
    }

    /**
     * 输出JSON信息
     *
     * @param mixed $data
     * @param bool $status
     * @param array $exts
     * @return string
     */
    public function outputJSON($data = null, $status = true, $exts = array())
    {
        $result = array(
            'status' => $status,
            'data'   => $data
        );
        $this->ajaxReturn(array_merge($result, $exts));
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
