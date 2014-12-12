<?PHP
namespace App\Controller;
use Think\Controller;

/**
 * 公共控制器
 *
 * @author popfeng <popfeng@yeah.net>
 */
class AbstractController extends Controller
{
    /**
     * 输出JSON信息
     *
     * @param mixed $data
     * @param bool $status
     * @return string
     */
    public function outputJSON($data = null, $status = true)
    {
        $this->ajaxReturn(
            array(
                'status' => $status,
                'data'   => $data
            )
        );
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
