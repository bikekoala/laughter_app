<?PHP
namespace App\Action;

use \App\Service\JPush\Push;

class TestAction extends AbstractAction
{
    public function index()
    {
        echo '<PRE>';
        $pushService = new Push(Push::OP_UP_CMT);
        $pushService->setUserToken('xxx'); // 用户数据库中的token
        $pushService->setUserName('树袋大熊');
        $pushService->setJokeId(1);
        $pushService->setJokeUserId(1);
        $pushService->setJokeUserName('讲个笑话吧');
        $pushService->setOpUserId(1);
        $pushService->setOpUserName('花毛小兔');
        $pushService->setOpUserAvatar('http://www.jgxhb.com/img/avatar_6_1417690373580.jpg');
        $pushService->setOpUserLevel(5);
        //$pushService->setSourceContent('好好笑阿～');

        $pushService->fire();
    }
}
