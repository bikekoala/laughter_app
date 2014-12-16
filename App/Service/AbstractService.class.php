<?PHP
namespace App\Service;

use Think\Controller;

/**
 * 服务抽象类
 *
 * @author popfeng <popfeng@yeah.net>
 */
class AbstractService
{
    /**
     * 笑话ID
     *
     * @var string
     */
    public $jokeId;

    /**
     * 用户ID
     *
     * @var string
     */

    public $userId;

    /**
     * 构造函数
     *
     * @param int $jokeId
     * @param int $userId
     * @return void
     */
    public function __construct($jokeId = 0, $userId = 0)
    {
        $this->jokeId = $jokeId;
        $this->userId = $userId;
    }
    /**
     * 补全图片URL
     *
     * @param string $path
     * @return string
     */
    public static function fillImageUrl($path)
    {
        $path = trim($path);
        if ('' !== $path && 'http' !== substr($path, 0, 4)) {
            $path = C('PORTAL_URL') . $path;
        }
        return $path;
    }

    /**
     * 判断是否已操作
     *
     * @param object $model
     * @param mixed $id
     * @return mixed
     */
    protected function _isAction(\App\Model\AbstractActionRecord $model, $id)
    {
        if ($this->userId) {
            if (is_array($id)) {
                $data = $model->getDatas($id, $this->userId);
                $dataCopy = array();
                foreach ($data as $item) {
                    $dataCopy[$item[$model->cateIdFieldName]] = true;
                }
                $result = array();
                foreach ($id as $v) {
                    $result[$v] = isset($dataCopy[$v]);
                }
                return $result;
            } else {
                $data = $model->getData($id, $this->userId);
                return ! empty($data);
            }
        } else {
            return false;
        }
    }
}
