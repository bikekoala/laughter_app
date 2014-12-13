<?PHP
namespace App\Service;
use App\Service\AbstractService;

/**
 * 笑话逻辑服务
 *
 * @author popfeng <popfeng@yeah.net>
 */
class Joke extends AbstractService
{
    /**
     * 根据ID获取笑话详情 
     *
     * @param int $id
     * @throws Exception
     * @return array
     */
    public function getDetail($id)
    {
        $detail = (new \App\Model\Joke)->getDetail($id);
        if (empty($detail)) {
            throw new \Exception('空的笑话详情');
        }
        return $detail;
    }
}
