<?php
/**
 * UserMenuComposer.php
 *
 * @author: David Baker <dbaker@acorncomputersolutions.com
 * Date: 7/8/14
 * Time: 10:17 AM
 */

namespace Botangle\Composers;
use Coderabbi\Virtuoso\Composer;

class UserMenuComposer implements Composer
{
    public function compose($view)
    {
        $menu = \Menu::handler('user-sidebar');

        $menu->add(route('user.my-account'), trans('My Account'));
        if(\Auth::user()->isTutor()) {
            $menu->add('/', trans('My Profile')); // @TODO: fix the link here to work properly ( /user/{username} )
        }

        $menu->add('/users/messages', trans('Messages'));
        // @TODO: add in an unread messages badge: echo $this->User->Getunreadmessage($this->Session->read('Auth.User.id'));
        // <span class="badge pull-right"></span>

        $menu->add('/users/lessons', trans('Lessons'));
        // @TODO: add in an unread messages badge: echo $this->User->Getunreadlesson($this->Session->read('Auth.User') );
        // <span class="badge pull-right"></span>
        $menu->add('/users/billing', trans('Billing'));

        $menu->getItemsByContentType('Menu\Items\Contents\Link')
            ->map(function($item)
                {
                    $item->getContent()->title = $item->getContent()->getValue();

                    if($item->isActive()) {
                        $item->getContent()->addClass('active');
                    }
                });

        /*
        * @TODO: other pages to add long-term
        * - Account Settings
        * - Invite Users
        * - Payment Settings? (what's the difference between this and Billing?)
        * - $menu->add('/users/mycalender', trans('My Calendar')); // @TODO: add this in later, we had a mockup, but never let users hit it
        *
        */
    }
}