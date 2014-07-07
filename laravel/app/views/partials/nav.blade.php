<div class="navbar navbar-default navbar-fixed-top  cbp-af-header" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <div class="Beta-tag">&nbsp;</div>
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
            <a href="{{{ action('PageController@getIndex') }}}" class="navbar-brand" title="{{{ trans('Home') }}}"><img src="/img/logo.png" alt="{{{ trans('Home') }}}"></a>
        </div>
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right">
                <li class="Home">
                    {{ link_to_action('PageController@getIndex', trans('Home'), [], ['class' => 'home', 'title' => trans('Home')]) }}
                </li>
                <li>
                    {{ link_to_action('PageController@getHowItWorks', trans('How it Works'), [], ['class' => 'category', 'title' => trans('How it Works')]) }}
                </li>
                <li>
                    {{ link_to_action('CategoryController@getIndex', trans('Categories'), [], ['class' => 'category', 'title' => trans('Categories')]) }}
                </li>
                <li>
                    {{ link_to_action('UsersController@getTopChart', trans('Top Charts'), [], ['class' => 'chart', 'title' => trans('Top Charts')]) }}
                </li>
                <li>
                    {{ link_to_action('PageController@getAbout', trans('About Us'), [], ['class' => 'about', 'title' => trans('About Us')]) }}
                </li>
                <li>
                    @if(Auth::check())
                    {{ link_to_action('UserController@getMyAccount', trans('My Account'), [], ['class' => 'myaccount', 'title' => trans('My Account')]) }}
                    @else
                    {{ link_to_action('UserController@getLogin', trans('Sign in'), [], ['class' => 'signin', 'title' => trans('Sign in')]) }}
                    @endif
                </li>
                <li>
                    {{ link_to_action('PageController@getReportbug', trans('Report a Bug'), [], ['class' => 'Report_Bug', 'title' => trans('Report a Bug')]) }}
                </li>
            </ul>
        </div>
        <!--/.nav-collapse -->
    </div>
</div>