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
        // @TODO: add caching here
        $view->with('usersJoined', \User::active()->count());
    }
}