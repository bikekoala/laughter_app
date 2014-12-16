<?PHP
namespace App\Service\JPush\Push;

use App\Service\JPush\Push\Consts;
use Common\Addons\Curl;

/**
 * JPush Push API v3
 *
 * @author popfeng <popfeng@yeah.net>
 */
class API 
{
    /**
     * 推送设备指定的别名
     *
     * @var array
     */
    protected $_audienceAlias;

    /**
     * 推送平台设置
     *
     * @var array
     */
    protected $_platform = array(Consts::PF_ANDROID);

    /**
     * 消息内容本身
     * 必须
     *
     * @var string
     */
    protected $_messageContent;

    /**
     * 消息标题 
     * 可选
     *
     * @var string
     */
    protected $_messageTitle;

    /**
     * 可选参数
     *
     * @var array
     */
    protected $_messageExtras;

    /**
     * 离线消息保留时长
     *
     * @var int
     */
    protected $_timeToLive = 86400;

    /**
     * APNs是否生产环境
     *
     * @var bool
     */
    protected $_apnsProduction = true;

    /**
     * 设置特定推送平台
     *
     * @param array $platform
     * @return void
     */
    public function setPlatform(...$platform)
    {
        if ( ! empty($platform)) {
            $this->_platform = $platform;
        }
    }

    /**
     * 推送设备指定的alias
     *
     * @param array $alias
     * @return void
     */
    public function setAudienceAlias(...$alias)
    {
        if ( ! empty($alias)) {
            $this->_audienceAlias = $alias;
        }
    }

    /**
     * 设置消息内容本身
     *
     * @param string $content
     * @return void
     */
    public function setMessageContent($content)
    {
        $this->_messageContent = $content;
    }

    /**
     * 设置消息标题
     *
     * @param string $title
     * @return void
     */
    public function setMessageTitle($title)
    {
        $this->_messageTitle = $title;
    }

    /**
     * 设置可选参数
     *
     * @param array $array
     * @return void
     */
    public function setMessageExtras($arr)
    {
        $this->_messageExtras = $arr;
    }

    /**
     * 执行推送
     *
     * @return void
     */
    public function run()
    {
        $body = $this->_getRequestBody();
        $conf = C('JPUSH');
        $authString = $conf['APP_KEY'] . ':' . $conf['MASTER_SECRET'];

        $curl = new Curl($conf['API']);
        $curl->setopt(CURLOPT_USERPWD, $authString);
        $curl->setopt(CURLOPT_TIMEOUT_MS, 1); // 毫秒超时
        return $curl->post($body);
    }

    /**
     * 获取请求内容
     *
     * @return string
     */
    protected function _getRequestBody()
    {
        $data = array(
            'platform' => $this->_platform,
            'audience' => $this->_audienceAlias,
            'message' => array(
                'msg_content' => $this->_messageContent,
                'title' => $this->_messageTitle,
                'extras' => $this->_messageExtras
            ),
            'options' => array(
                'time_to_live' => $this->_timeToLive,
                'apns_production' => $this->_apnsProduction
            )
        );

        $data = array_filter_recursive($data);
        return json_encode($data);
    }
}
