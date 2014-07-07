<div class="navbar navbar-default navbar-fixed-top  cbp-af-header" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <div class="Beta-tag">&nbsp;</div>
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
            <a href="{{{ route('home') }}}" class="navbar-brand" title="{{{ trans('Home') }}}"><img src="/img/logo.png" alt="{{{ trans('Home') }}}"></a>
        </div>
        <div class="navbar-collapse collapse">
            {{ Menu::handler('main')->render() }}
        </div>
        <!--/.nav-collapse -->
    </div>
</div>