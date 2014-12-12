<?PHP
namespace App\Controller;

/**
 * 空控制器类
 *
 * @author popfeng <popfeng@yeah.net>
 */
class EmptyController extends AbstractController
{
    /**
     * 入口
     *
     * @return void
     */
    public function index()
    {
        $this->_empty();
    }
}
