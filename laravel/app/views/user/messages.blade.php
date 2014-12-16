@extends('user.layout')

@section('head')
@parent
{{ Html::script('js/jquery.nicescroll.min.js') }}
@stop

@section('page-content')
    <div class="StaticPageRight-Block">
        <div class="row-fluid">
            <div class="span4 Message-List-Block">
                @foreach($userList as $listUser)
                    <a title="{{{ $listUser->username }}}" href="{{ url('user/messages', $listUser->username) }}">
                        <div class="Message-row {{ ($listUser->id == $viewingUser->id ? 'viewing-message' : '') }}">
                            <div class="row-fluid">
                                <div class="span4 sender-img">
                                    {{ Html::image(url($listUser->picture), $listUser->username, array('class' => 'img-circle', 'style' => 'width="58px" height="58px"')) }}
                                </div>
                                <div class="span8 sender-name">
                                    {{ $listUser->fullName }}<br>
                                    <span class="FontStyle11">
                                        {{ $listUser->formatLastMessageDateForHumans() }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
            <div class="span8 Message-detail-Block" id="boxscroll">
                <div class="Message-lists">
                    @foreach($messages as $message)
                        <div class="row-fluid">
                            @if($message->sent_from == Auth::user()->id)
                            <div class="span2 sender-img">
                                {{ Html::image(url($message->sender->picture), $message->sender->username, array('class' => 'img-circle', 'style' => 'width="58px" height="58px"')) }}
                            </div>
                            <div class="span10 sender-text">
                                <div id="tip-left">&nbsp;</div>
                            @else
                                <div class="span10 sender-text">
                            @endif
                                <p class="sender-name">
                                    @if($message->sent_from == Auth::user()->id)
                                        {{ trans('You') }}
                                    @else
                                        {{{ $message->sender->fullName }}}
                                    @endif
                                </p>

                                <p class="msg-content">
                                    {{ $message->body }}</p>

                                <p class="msg-time">{{ \Carbon\Carbon::createFromTimeStamp(strtotime($message->date))->diffForHumans() }}</p>
                            @if($message->sent_from != Auth::user()->id)
                                <div id="tip-right">&nbsp;</div>
                            </div>
                            <div class="span2 sender-img">
                                {{ Html::image(url($message->sender->picture), $message->sender->username, array('class' => 'img-circle', 'style' => 'width="58px" height="58px"')) }}
                            </div>
                            @else
                            </div>
                            @endif
                        </div>
                    @endforeach

                    {{ Former::open()
                    ->method('POST')
                    ->class('form-inline form-horizontal')
                    ->route('user-message.create')
                    ->data_async()
                    ->id('messageForm')
                    }}

                    {{ Former::hidden('send_to', $viewingUser->id) }}

                    <div id="Write-msg">

                        {{ Form::textarea('body', '', array(
                            'placeholder'   => trans('Type Your message'),
                            'rows'          => 3,
                            'class'         => 'textarea',
                        )) }}

                        <div class="span5 pull-right msg-send-btn">
                            {{ Former::submit(trans('Submit'))
                            ->addClass('btn btn-primary')
                            ->name('submit')
                            }}
                        </div>
                    </div>
                    {{ Former::close() }}

                </div><!-- End Message-lists -->
            </div><!-- End span8 Message-List-Block -->
        </div><!-- End row-fluid -->
    </div><!-- End StaticPageRight-Block -->

@stop

@section('jsFiles')
@parent
<script>
    $ = jQuery.noConflict();
    $(".Message-lists").niceScroll({cursorborder: "", cursorcolor: "#F38918", boxzoom: true}); // First scrollable DIV
    var $t = $('.Message-lists');
    $t.animate({"scrollTop": $('.Message-lists')[0].scrollHeight}, "slow");
</script>
@stop