@if(Auth::check())
    <header id="Bannerblock2">
        <div class="container text-center">
            <div class="row-fluid">
                <div class="span4 Header-title01">
                    <p>&nbsp;<br>
                        <span>&nbsp;</span></p>
                </div>
                <div class="span3 pull-right">
                    <div class="Header-Account-info">
                        <span> Welcome {{{ Auth::user()->username }}} </span>
                        |
                        {{ HTML::link(route('logout'), trans('Sign Out'), ['class' => 'signin', 'title' => trans('Sign Out')]) }}
                    </div>
                    <form method="post" action="/user/search" id="searchuser">
                        <div class="Header-search">
                            <input name="searchvalue" id="searchvalue" type="text" style="line-height: 20px" />
                            {{ HTML::image('/img/search-img.jpg', trans('Search'), ['class' => 'submit', 'id' => 'search']) }}
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </header>
@else
    <header id="Bannerblock2">
        <div class="container text-center">
            <div class="row-fluid">
                <div class="span4 Header-title01">
                    <p>Join<br>
                        <span>Botangle</span></p>
                </div>
                <form method="post" action="/user/search" id="searchuser">
                    <div class="span3 pull-right">
                        <div class="Header-search">
                            <input name="searchvalue" id="searchvalue" type="text" style="line-height: 20px" />
                            {{ HTML::image('/img/search-img.jpg', trans('Search'), ['class' => 'submit', 'id' => 'search']) }}
                        </div>
                        <div class="Header-Free-info">{{ trans('Find help immediately!') }}<br>
                            <!--          <span>Try for 7 days free!</span> -->
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </header>
@endif

<script>
    var $j = jQuery.noConflict();

    jQuery(document).ready(function(){
        jQuery("#search").click(function(){
            jQuery("#searchuser").submit();
        })
    });
</script>
