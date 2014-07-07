<?php
/**
 * MainMenuComposer.php
 *
 * @author: David Baker <dbaker@acorncomputersolutions.com
 * Date: 7/7/14
 * Time: 5:03 PM
 */

namespace Botangle\Composers;
use Coderabbi\Virtuoso\Composer;

class MainMenuComposer implements Composer
{
    public function compose($view)
    {
        $menu = \Menu::handler('main', array('class' => 'nav navbar-nav navbar-right'));

        $menu
            ->add(route('home'), trans('Home'))
            ->add(route('how-it-works'), trans('How it Works'))
            ->add(route('categories.index'), trans('Categories'))
            ->add(route('users.topcharts'), trans('Top Charts'))
            ->add(route('about'), trans('About Us'));

        if(\Auth::check()) {
            $menu->add(route('user.my-account'), trans('My Account'));
        } else {
            $menu->add(route('login'), trans('Sign in'));
        }

        $menu
            ->add(route('reportbug'), trans('Report a Bug'))
            ->getItemsByContentType('Menu\Items\Contents\Link')
            ->map(function($item)
                {
                    $item->getContent()->title = $item->getContent()->getValue();

                    if($item->isActive()) {
                        $item->getContent()->addClass('active');
                    }

                    if($item->getContent()->getUrl() == route('home')) {
                        $item->getContent()->addClass('home');
                    }

                    if($item->getContent()->getUrl() == route('how-it-works')) {
                        $item->getContent()->addClass('category');
                    }

                    if($item->getContent()->getUrl() == route('categories.index')) {
                        $item->getContent()->addClass('category');
                    }

                    if($item->getContent()->getUrl() == route('users.topcharts')) {
                        $item->getContent()->addClass('chart');
                    }

                    if($item->getContent()->getUrl() == route('about')) {
                        $item->getContent()->addClass('about');
                    }

                    if($item->getContent()->getUrl() == route('user.my-account')) {
                        $item->getContent()->addClass('myaccount');
                    }

                    if($item->getContent()->getUrl() == route('login')) {
                        $item->getContent()->addClass('signin');
                    }

                    if($item->getContent()->getUrl() == route('reportbug')) {
                        $item->getContent()->addClass('Report_Bug');
                    }
                });
    }
}