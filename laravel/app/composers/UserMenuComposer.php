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
            $menu->add(
                action(
                    'UserController@getView',
                    array(
                        'username' => \Auth::user()->username,
                    )
                ),
                trans('My Profile')
            );
        }

        $menu->add(route('user.messages'), trans('Messages'));
        $menu->add(route('user.lessons'), trans('Lessons'));
        $menu->add(route('user.billing'), trans('Billing'));

        $menu->getItemsByContentType('Menu\Items\Contents\Link')
            ->map(function($item)
                {
                    $item->getContent()->title = $item->getContent()->getValue();

                    if($item->isActive()) {
                        $item->getContent()->addClass('active');
                    }

                    if($item->getContent()->getUrl() == route('user.messages')) {
                        $item->getContent()->nest(
                            '<span class="badge pull-right">X</span>'
                        );
                        // @TODO: add in an unread messages badge: echo $this->User->Getunreadmessage($this->Session->read('Auth.User.id'));
                    }

                    if($item->getContent()->getUrl() == route('user.lessons')) {
                        $item->getContent()->nest(
                            '<span class="badge pull-right">'.
                            \Lesson::active()->proposals()->unread(\Auth::user())->count()
                            .'</span>'
                        );
                        // @TODO: add in an unread lessons badge: echo $this->User->Getunreadlesson($this->Session->read('Auth.User') );
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