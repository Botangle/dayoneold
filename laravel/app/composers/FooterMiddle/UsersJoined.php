<?php
/**
 * UsersJoined.php
 *
 * @author: David Baker <dbaker@acorncomputersolutions.com
 * Date: 7/7/14
 * Time: 10:42 AM
 */

namespace Botangle\Composers\FooterMiddle;
use Coderabbi\Virtuoso\Composer;

class UsersJoined implements Composer
{
    public function compose($view)
    {
        if (!\Cache::has('users-joined')){
            $userCount = \User::active()->count();
            \Cache::forever('users-joined', $userCount);
        }
        $view->with('usersJoined', \Cache::get('users-joined'));
    }
}
