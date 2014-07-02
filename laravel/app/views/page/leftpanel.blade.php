<div class="span3 LeftMenu-Block">
    <ul>
        <li>
            {{ link_to_action('PageController@getReportbug', trans('About Us'), [], ['class' => '', 'title' => trans('About Us')]) }}
        </li>
        <li>
            {{ link_to_action('PageController@getReportbug', trans('Contact Us'), [], ['class' => '', 'title' => trans('Contact Us')]) }}
        </li>
        <li>
            {{ link_to_action('PageController@getReportbug', trans('Report Bug'), [], ['class' => '', 'title' => trans('Report Bug')]) }}
        </li>
    </ul>
</div>