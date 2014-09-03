<div class="row-fluid">
    <div class="span3 media-img">
        {{ HTML::image($news->image) }}
    </div>
    <div class="span9 media-text">
        @if(!isset($noHeading) || !$noHeading)
        <p class="FontStyle20">{{ Html::link(route('news.detail', $news->id), $news->title) }}</p>
        @endif
        <p>{{{ $news->details }}}</p>
        <br>
        <p>Posted on: {{ $news->date->format("M d,Y") }} </p>
    </div>
</div>
