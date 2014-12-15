<?PHP
namespace App\Action;

use Think\Action;

/**
 * 公共控制器
 *
 * @author popfeng <popfeng@yeah.net>
 */
class AbstractAction extends Action
{
    /**
     * 用户TID转换为ID
     *
     * @return void
     * @todo 解密
     */
    public function tidToId($userTid)
    {
        return 1;
    }

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
