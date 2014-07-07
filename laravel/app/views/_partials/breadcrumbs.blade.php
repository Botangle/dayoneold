@if ($breadcrumbs)
<div id="HomeServices">
    <div class="container">
        <div class=" row-fluid">
            <ul class="span12 breadcrumbs">
                @foreach ($breadcrumbs as $breadcrumb)
                    @if ($breadcrumb->first)
                        <li><a href="{{{ $breadcrumb->url }}}">{{{ $breadcrumb->title }}}</a></li>
                    @elseif (!$breadcrumb->last)
                        // <li><a href="{{{ $breadcrumb->url }}}">{{{ $breadcrumb->title }}}</a></li>
                    @else
                        // <li class="active">{{{ $breadcrumb->title }}}</li>
                    @endif
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endif