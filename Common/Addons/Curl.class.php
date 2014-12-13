<?PHP
namespace Common\Addons;

/**
 * curl库的封装
 *
 * @author sunxuewu <sunxw@ucloudworld.com>
 */
class Curl
{
    const FT_JSON   = 'json';
    const FT_SERIAL = 'serialize';

    const MD_GET    = 'GET';
    const MD_PUT    = 'PUT';
    const MD_POST   = 'POST';
    const MD_DELETE = 'DELETE';

    /**
     * _ch
     * curl 句柄
     *
     * @var resource
     */
    protected $_ch;

    /**
     * _url
     * url地址
     *
     * @var string
     */
    protected $_url;

    /**
     * _lastInfo
     * 最后执行信息
     *
     * @var array
     */
    protected $_lastInfo;

    /**
     * __construct
     * 构造函数
     *
     * @param string $url
     * @return void
     */
    public function __construct($url)
    {
        $this->_url = $url;
        $this->_ch = curl_init($url);
        curl_setopt($this->_ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->_ch, CURLOPT_BINARYTRANSFER, true);
    }

    /**
     * setopt
     * 设置curl选项
     *
     * @param mixed $type 需要设置的CURLOPT_XXX选项
     * @param mixed $val
     * @return void
     */
    public function setopt($type, $val)
    {
        curl_setopt($this->_ch, $type, $val);
    }

    /**
     * post
     * 发送post请求的快捷方法
     *
     * @param mixed $fields
     * @return string
     */
    public function post($fields = null)
    {
        curl_setopt($this->_ch, CURLOPT_POST, count($fields));
        curl_setopt($this->_ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($this->_ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($this->_ch);
        $this->_lastInfo = curl_getinfo($this->_ch);
        return $result;
    }

    /**
     * get
     * 发送get请求的快捷方法
     *
     * @param mixed $fields
     * @return string
     */
    public function get($fields = null)
    {
        if (is_array($fields)) {
            $info = curl_getinfo($this->_ch);    
            $url = $info['url'] . '?' . http_build_query($fields);
            curl_setopt($this->_ch, CURLOPT_URL, $url);
        }
        $result = curl_exec($this->_ch);
        $this->_lastInfo = curl_getinfo($this->_ch);
        return $result;
    }

    /**
     * rest
     * 接口请求处理,支持serialize&json
     *
     * @param mixed $fields
     * @param string $method GET|POST|PUT|DELETE
     * @param string $format
     * @return array
     */
    public function rest($fields = null, $method, $format = null)
    {
        $data = $method == MD_POST ? $this->post($fields) : $this->get($fields);
        $data = preg_replace('/[^\x20-\xff]*/', '', $data); //清除不可见字符
        $data = iconv('utf-8', 'utf-8//ignore', $data); //UTF-8转码
        switch ($format) {
            case static::FT_SERIAL :
                if (false === ($result = unserialize($data))) {
                    throw new \Exception(
                        'unserialize error' . $data,
                        $this->_lastInfo['http_code']
                    );
                }
                break;
            case static::FT_JSON :
            default :
                if (false === ($result = json_decode($data, true))) {
                    throw new \Exception(
                        'json_decode error' . $data,
                        $this->_lastInfo['http_code']
                    );
                }
        }
        return $result;
    }

    /**
     * lastInfo
     * 最后一次请求的信息记录
     *
     * @return void
     */
    public function lastInfo()
    {
        return $this->_lastInfo;
    }
}
