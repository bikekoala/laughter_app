<?PHP
namespace App\Service\JPush;
use App\Service\JPush\Push\API;
use App\Service\JPush\Push\Consts;

/**
 * 客户端消息推送服务
 *
 * @author popfeng <popfeng@yeah.net>
 */
class PushService
{
    /**
     * 操作类型
     */
    const OP_RE_JOKE = 0; // 评论笑话
    const OP_RE_CMT  = 1; // 回复评论
    const OP_UP_JOKE = 2; // 笑话点赞
    const OP_UP_CMT  = 3; // 评论点赞

    /**
     * 跳转类型
     */
    const RD_HOME    = 0; // 主页
    const RD_DETAIL  = 1; // 详情页
    const RD_MESSAGE = 2; // 消息业
    const RD_CUSTOM  = 3; // 自定义URL

    /**
     * 操作类型
     *
     * @var int
     */
    protected $_opType;

    /**
     * 用户TOKEN
     *
     * @var string
     */
    protected $_userToken;

    /**
     * 推送标题
     *
     * @var string
     */
    protected $_title = '讲个笑话吧';

    /**
     * 推送内容
     *
     * @var string
     */
    protected $_content;

    /**
     * 笑话ID
     *
     * @var int
     */
    protected $_jokeId;

    /**
     * 操作用户ID
     *
     * @var int
     */
    protected $_opUserId;

    /**
     * 操作用户名称
     *
     * @var string
     */
    protected $_opUserName;

    /**
     * 操作用户头像URL
     *
     * @var string
     */
    protected $_opUserAvatar;

    /**
     * 操作用户级别
     *
     * @var int
     */
    protected $_opUserLevel;

    /**
     * 评论或回复的源内容
     *
     * @var string
     */
    protected $_sourceContent;

    /**
     * 跳转类型
     *
     * @var string
     */
    protected $_redirectType = self::RD_DETAIL;

    /**
     * 指定跳转的自定义URL地址
     *
     * @var string
     */
    protected $_redirectUrl;

    /**
     * 构造方法
     *
     * @param int $opType
     * @return void
     */
    public function __construct($opType)
    {
        $this->_opType = $opType;
    }

    /**
     * 设置用户TOKEN
     *
     * @param string $token
     * @return void
     */
    public function setUserToken($token)
    {
        $this->_userToken = $token;
    }

    /**
     * 设置用户名称
     *
     * @param string $name
     * @return void
     */
    public function setUserName($name)
    {
        $this->setComment($name, $this->_opType);
    }

    /**
     * 设置推送内容
     *
     * @param string $name
     * @param int $opType
     * @return void
     */
    public function setComment($name, $opType)
    {
        $opTypeStr = '';
        $cateName = '';
        switch ($opType) {
            case self::OP_RE_JOKE :
                $opTypeStr = '评论';
                $cateName = '笑话';
                break;
            case self::OP_RE_CMT :
                $opTypeStr = '回复';
                $cateName = '评论';
                break;
            case self::OP_UP_JOKE :
                $opTypeStr = '赞';
                $cateName = '笑话';
                break;
            case self::OP_UP_CMT :
                $opTypeStr = '赞';
                $cateName = '评论';
                break;
        }

        $this->_content = sprintf('%s%s了您的%s', $name, $opTypeStr, $cateName);
    }

    /**
     * 设置笑话ID
     *
     * @param int $id
     * @return void
     */
    public function setJokeId($id)
    {
        $this->_jokeId = $id;
    }

    /**
     * 设置操作用户ID
     *
     * @param int $id
     * @return void
     */
    public function setOpUserId($id)
    {
        $this->_opUserId = $id;
    }

    /**
     * 设置操作用户名称
     *
     * @param string $name
     * @return void
     */
    public function setOpUserName($name)
    {
        $this->_opUserName = $name;
    }

    /**
     * 色沪指操作用户头像URL
     *
     * @param string $url
     * @return void
     */
    public function setOpUserAvatar($url)
    {
        $this->_opUserAvatar = $url;
    }

    /**
     * 设置操作用户级别
     *
     * @param int $level
     * @return void
     */
    public function setOpUserLevel($level)
    {
        $this->_opUserLevel = $level;
    }

    /**
     * 设置评论或回复的源内容（只需评论或回复操作时设置）
     *
     * @param string $content
     * @return void
     */
    public function setSourceContent($content)
    {
        $this->_sourceContent = $content;
    }

    /**
     * 设置跳转类型
     *
     * @param int $type
     * @return void
     */
    public function setRedirectType($type)
    {
        $this->_redirectType = $type;
    }

    /**
     * 设置跳转的自定义URL
     *
     * @param string $url
     * @return void
     */
    public function setRedirectUrl($url)
    {
        $this->_redirectUrl = $url;
    }

    /**
     * 执行推送命令
     *
     * @return void
     */
    public function fire()
    {
        $pushApi = new API;
        $pushApi->setPlatform(Consts::PF_ANDROID);
        $pushApi->setMessageTitle($this->_title);
        $pushApi->setMessageContent($this->_content);
        $pushApi->setMessageExtras(array(
            'user_tid' => md5($this->_userToken),
            'joke_id' => $this->_jokeId,
            'op_type' => $this->_opType,
            'op_user_id' => $this->_opUserId,
            'op_user_name' => $this->_opUserName,
            'op_user_avatar' => $this->_opUserAvatar,
            'op_user_level' => $this->_opUserLevel,
            'op_content' => $this->_content,
            'op_time' => date('Y-m-d H:i:s'),
            'source_content' => $this->_sourceContent,
            'redirect_type' => $this->_redirectType,
            'redirect_url' => $this->_redirectUrl
        ));

        $pushApi->run();
    }
}
