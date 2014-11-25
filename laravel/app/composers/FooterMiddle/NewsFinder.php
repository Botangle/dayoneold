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

class NewsFinder implements Composer
{
    public function compose($view)
    {
        $view->with('news', $this->getMyData()); //
    }

    private function getMyData()
    {
        if (!\Cache::has('latest-news')){
            $news = \News::where('status', 1)->limit(3)->orderBy('date', 'DESC')->get();
            \Cache::put('latest-news', $news, 10080);
        }
        return \Cache::get('latest-news');
    }

}
