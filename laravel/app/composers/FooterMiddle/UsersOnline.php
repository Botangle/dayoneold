<?php
/**
 * UsersOnline.php
 *
 * @author: David
 * Date: 7/7/14
 * Time: 10:42 AM
 */

namespace Botangle\Composers\FooterMiddle;
use Coderabbi\Virtuoso\Composer;

class UsersOnline implements Composer
{
    public function compose($view)
    {
        if (!\Cache::has('users-online')){
            $userCount = \User::active()->online()->count();
            \Cache::forever('users-online', $userCount);
        }
        $view->with('usersOnline', \Cache::get('users-online'));
    }
}
