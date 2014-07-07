<div id="main-content1">
    <div class="container">
        <div class="row-fluid">
            <div class="span3 joined-member-box">
                <div class="joined-member"> Joined members
                    <p><p>Needed - userjo</p></p>
                </div>
                <div class="joined-member"> Online members
                    <p><p>Needed - useron</p></p>
                </div>
            </div>
            <div class="span5 social-updates">

                <div class="facebook-box">
                    <p class="title1">Likes on Facebook</p>

                    <iframe src="//www.facebook.com/plugins/likebox.php?href=https%3A%2F%2Fwww.facebook.com%2FBotangle&amp;width=430&amp;height=258&amp;colorscheme=dark&amp;show_faces=true&amp;header=false&amp;stream=false&amp;show_border=false" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:430px; height:258px;" allowTransparency="true"></iframe>
                </div>
            </div>

            $news = $this->Custom->getNewsList();

            <div class="span4 latest-news-box">
                <div class="latest-news-box">
                    <p class="title1">{{ trans("Latest News") }}</p>
                    @if(!empty($news)) {
                        @foreach($news as $k=>$v)
                            {{--
                            <div class="media latest-news1">
                                $date = date('M',strtotime($v['News']['date']));
                                $day = date('d',strtotime($v['News']['date']));
                                <div class="pull-left media-date">
                                    <div class="date"> $day </div>
                                    <div class="month"> $date </div>
                                </div>

                                <div class="media-body">
                                    <h4 class="media-heading1">
                                        $v['News']['title'] </h4>
                                    <a href="/news/detail/ str_replace(" ","-",$v['News']['title']) / $v['News']['id'] ">Read more</a></div>
                            </div>
                            --}}
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>