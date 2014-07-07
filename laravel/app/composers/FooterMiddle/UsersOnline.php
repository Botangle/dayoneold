<?php
/**
 * UsersOnline.php
 *
 * @author: David Baker <dbaker@acorncomputersolutions.com
 * Date: 7/7/14
 * Time: 10:42 AM
 */

namespace Botangle\Composers\FooterMiddle;
use Coderabbi\Virtuoso\Composer;

class UsersOnline implements Composer
{
    public function compose($view)
    {
        // @TODO: add caching here
        $view->with('usersOnline', \User::active()->online()->count());
    }
}