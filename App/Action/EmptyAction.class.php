<?PHP
namespace App\Action;

/**
 * 空控制器类
 *
 * @author popfeng <popfeng@yeah.net>
 */
class EmptyAction extends AbstractAction
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
