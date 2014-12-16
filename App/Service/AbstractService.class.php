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
}
