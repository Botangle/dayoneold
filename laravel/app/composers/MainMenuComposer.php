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
	    $this->registerMainMenu();
	    $this->registerMainSecondaryMenu();
    }

	public function registerMainMenu()
	{
		$menu = \Menu::handler('main', array('class' => 'nav navbar-nav'));

		$menu
			->add(route('home'), trans('Home'))
			->add('http://forum.startdayone.com/', trans('Forum'));

		$menu
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
			});
	}

	public function registerMainSecondaryMenu()
	{
		$menu = \Menu::handler('main-secondary', array('class' => 'nav navbar-nav navbar-right'));

		$menu
			->add('', trans('Want to Stream?'));

		if(\Auth::check()) {
			$menu->add(route('new.stream.create'), trans('Start Broadcasting'));
			$menu->add(route('logout'), trans('Logout'));
		} else {
			$menu->add(route('login'), trans('Start Broadcasting'));
		}

		$menu
			->getItemsByContentType('Menu\Items\Contents\Link')
			->map(function($item)
			{
				$item->getContent()->title = $item->getContent()->getValue();

				if($item->isActive()) {
					$item->getContent()->addClass('active');
				}

				if($item->getContent()->getUrl() == route('about')) {
					$item->getContent()->addClass('about');
				}

				if($item->getContent()->getUrl() == route('new.user.my-account')) {
					$item->getContent()->addClass('my-account');
				}

				if($item->getContent()->getUrl() == route('login')) {
					$item->getContent()->addClass('login');
				}
			});
	}
}
