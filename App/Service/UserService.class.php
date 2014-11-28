<?PHP
namespace App\Service;
use App\Service\AbstractService;

/**
 * 用户逻辑服务
 *
 * @author popfeng <popfeng@yeah.net>
 */
class UserService extends AbstractService
{
    /**
     * 根据ID获取用户详情
     *
     * @param int $id
     * @throws Exception
     * @return array
     */
    public function getDetail($id)
    {
        $detail = (new \App\Model\UsersModel)->getDetail($id);
        if (empty($detail)) {
            throw new \Exception('空的用户详情');
        } else {
            $detail['avatar'] = static::fillImageUrl($detail['avatar']);
        }

        return $detail;
    }
}
