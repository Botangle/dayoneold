<?php
/**
 * UsersJoined.php
 *
 * @author: David Baker <dbaker@acorncomputersolutions.com
 * Date: 7/7/14
 * Time: 10:42 AM
 */

namespace Botangle\Composers;
use Coderabbi\Virtuoso\CompositeComposer;

class FooterMiddleComposer extends CompositeComposer
{
    protected $composers = array(
        'Botangle\Composers\FooterMiddle\NewsFinder',
        'Botangle\Composers\FooterMiddle\UsersJoined',
        'Botangle\Composers\FooterMiddle\UsersOnline',
    );
}