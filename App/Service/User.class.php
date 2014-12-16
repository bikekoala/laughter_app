<?PHP
namespace App\Service;

use App\Service\AbstractService;

/**
 * 用户逻辑服务
 *
 * @author popfeng <popfeng@yeah.net>
 */
class User extends AbstractService
{
    /**
     * 根据ID获取用户数据
     *
     * @param int $id
     * @throws Exception
     * @return array
     */
    public function getData($id)
    {
        $data = (new \App\Model\Users)->getData($id);
        if (empty($data)) {
            throw new \Exception('空的用户详情');
        } else {
            $data['avatar'] = static::fillImageUrl($data['avatar']);
        }

        return $data;
    }

    /**
     * 解密用户ID
     *
     * @param string $tid
     * @return int
     * @todo
     */
    public static function decryptUserId($tid)
    {
        return 1;
    }
}
