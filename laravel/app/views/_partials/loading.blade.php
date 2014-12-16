<div id="loading-div-background">
    <div id="loading-div" class="ui-corner-all">
        <h3>{{ $title }}</h3>
        <p>This may take a while, please wait...<br>
            {{ HTML::image(url("/js/select2/select2-spinner.gif"), 'Processing...') }}
        </p>
    </div>
</div>
