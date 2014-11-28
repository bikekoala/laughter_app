<?PHP
namespace App\Controller;
use Think\Controller;

/**
 * 空控制器类
 *
 * @author popfeng <popfeng@yeah.net>
 */
class EmptyController extends Controller
{
    /**
     * 入口
     *
     * @return void
     */
    public function index()
    {
        $msg = '我有一头小毛驴呀我从来也不骑～';
        $url = C('PORTAL_URL');
        $this->error($msg, $url);
    }
}
