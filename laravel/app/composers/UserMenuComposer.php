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
		$this->sidebar();
		$this->oldSidebar();
	}

	public function sidebar()
	{
		$menu = \Menu::handler('new-user-sidebar');

		$menu->add('#', trans('My Account'));
//		$menu->add(route('new.user.my-account'), trans('My Account'));
//		if(\Auth::user()->isTutor()) {
//			$menu->add(
//				action(
//					'UserController@getView',
//					array(
//						'username' => \Auth::user()->username,
//					)
//				),
//				trans('My Profile')
//			);
//		}
//
//		$menu->add(route('user.messages'), trans('Messages'));
//		$menu->add(route('user.lessons'), trans('Lessons'));
//		$menu->add(route('user.billing'), trans('Billing'));
//		$menu->add(route('user.credit'), trans('Credits'));
	}

    public function oldSidebar()
    {
        $menu = \Menu::handler('user-sidebar');

        $menu->add(route('new.user.my-account'), trans('My Account'));
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
        $menu->add(route('user.credit'), trans('Credits'));

        $menu->getItemsByContentType('Menu\Items\Contents\Link')
            ->map(function($item)
                {
                    $item->getContent()->title = $item->getContent()->getValue();

                    if($item->isActive()) {
                        $item->getContent()->addClass('active');
                    }

                    if($item->getContent()->getUrl() == route('user.messages')) {
                        $item->getContent()->nest(
                            '<span class="badge pull-right">'.
                            \UserMessage::toUser(\Auth::user())->unread()->count().'</span>'
                        );
                    }

                    if($item->getContent()->getUrl() == route('user.lessons')) {
                        $item->getContent()->nest(
                            '<span class="badge pull-right">'.
                            \Lesson::active()->proposals()->unread(\Auth::user())->count()
                            .'</span>'
                        );
                    }

                    if ($item->getContent()->getUrl() == route('user.credit')) {
                        $item->getContent()->nest(
                            '<span class="badge pull-right">'.
                            number_format(\Auth::user()->creditAmount, 2)
                            .'</span>'
                        );
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
