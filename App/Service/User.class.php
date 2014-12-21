<?PHP
namespace App\Service;

use \App\Model\Users;

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
        $data = (new Users)->getData($id);
        if (empty($data)) {
            throw new \Exception('空的用户详情');
        } else {
            $data['avatar'] = static::fillImageUrl($data['avatar']);
        }

        return $data;
    }

    /**
     * 通过TOKEN获取用户ID
     *
     * @param string $token
     * @return int
     */
    public function tokenToId($token)
    {
        $data = (new Users)->getDataByToken($token);
        if ( ! empty($data)) {
            return $data['id'];
        } else {
            return 0;
        }
    }

    /**
     * 解密用户TOKEN
     *
     * @param string $tid
     * @return int
     */
    public static function decryptUserToken($tid)
    {
        $tid = base64_decode($tid);
        list($token, $timestamp) = explode('_', $tid);

        $key = C('CLIENT_SECRET');
        $iv = substr(md5($timestamp . '@k#' . $key), -16);

        $str = base64_decode($token);
        $result = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $str, MCRYPT_MODE_CBC, $iv);
        $padLength = hexdec(bin2hex($result[strlen($result) - 1]));
        return substr($result, 0, strlen($result) - $padLength);
    }
}
