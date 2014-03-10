<?php
		 if($this->Session->read('Auth.User.id')){ ?>

<header id="Bannerblock2">
    <div class="container text-center">
        <div class="row-fluid">
            <div class="span4 Header-title01">
                <p>&nbsp;<br>
                    <span>&nbsp;</span></p>
            </div>
            <div class="span3 pull-right">
                <div class="Header-Account-info"><span> Welcome <?php echo ucfirst($this->
                    Session->read('Auth.User.username')) ?>  </span> |  <?php
		
		 echo $this->Html->link(__('Sign Out'), '/logout',
                    array('class' => 'signin', 'title' => __('Sign Out')));
                    ?>
                </div>
                <form method="post" action="<?php echo $this->webroot?>user/search" id="searchuser">
                    <div class="Header-search">
                        <input name="searchvalue" id="searchvalue" type="text">
                        <?php echo $this->
                        Html->image('/croogo/img/search-img.jpg',array('class'=>'submit','id'=>'search'));?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</header>
<?php }else{ ?>
<header id="Bannerblock2">
    <div class="container text-center">
        <div class="row-fluid">
            <div class="span4 Header-title01">
                <p>Join<br>
                    <span>Botangle</span></p>
            </div>
            <form method="post" action="<?php echo $this->webroot?>user/search" id="searchuser">
                <div class="span3 pull-right">
                    <div class="Header-search">
                        <input name="searchvalue" id="searchvalue" type="text"/>
                        <?php echo $this->
                        Html->image('/croogo/img/search-img.jpg',array('class'=>'submit','id'=>'search'));?>
                    </div>
                    <div class="Header-Free-info"> Find help immediatly?<br>
                        <span>Try for 7 days free!</span></div>
                </div>
            </form>
        </div>
    </div>
</header>
<?php } 
 echo $this->Html->scriptBlock(
'var $j = jQuery.noConflict();

jQuery(document).ready(function(){
jQuery("#search").click(function(){
jQuery("#searchuser").submit();
})
})',
array('inline' => true)
);

?>
<?php
echo $this->Layout->js();
echo $this->Html->script(array( '/croogo/js/autocomplete/jquery-1.9.1',
'/croogo/js/autocomplete/jquery.ui.core','/croogo/js/autocomplete/jquery.ui.widget','/croogo/js/autocomplete/jquery.ui.position','/croogo/js/autocomplete/jquery.ui.menu','/croogo/js/autocomplete/jquery.ui.autocomplete',
));

echo $this->Html->css(array(
'/croogo/css/autocomplete/themes/base/jquery.ui.all', '/croogo/css/autocomplete/demos',
));
?>
<script>
    var suburl = "/demos/botangle/subject/search";
    if (document.URL.indexOf('127.0.0.1') >= 0) {
        var suburl = "/botangle/subject/search"
    }
    var $j = jQuery.noConflict();
    datasubject = "";
    jQuery(function () {
        jQuery.getJSON(suburl, function (response) {
            datasubject = response;
            console.log(datasubject)
            $j("#searchvalue,#LessonSubject").autocomplete({
                minLength: 0,
                source: datasubject,
                focus: function (event, ui) {

                    $j("#searchvalue,#LessonSubject").val(ui.item.label);
                    return false;
                },
                select: function (event, ui) {

                    $j("#searchvalue,#LessonSubject").val(ui.item.label);
                    return false;
                }
            })
                    .data("ui-autocomplete")._renderItem = function (ul, item) {
                return $j("<li>")
                        .append("<a>" + item.label + "</a>")
                        .appendTo(ul);
            };
            if (document.URL.indexOf('createlesson') >= 0) {
                $j("#LessonSubject").autocomplete({
                    minLength: 0,
                    source: data,
                    focus: function (event, ui) {
                        console.log(this.id)
                        $j("#LessonSubject").val(ui.item.label);
                        return false;
                    },
                    select: function (event, ui) {
                        console.log(this.id)
                        $j("#LessonSubject").val(ui.item.label);
                        return false;
                    }
                })
                        .data("ui-autocomplete")._renderItem = function (ul, item) {
                    return $j("<li>")
                            .append("<a>" + item.label + "</a>")
                            .appendTo(ul);
                };
            }
        })

    });

</script>

<script src="<?php echo $this->webroot?>Croogo/js/jquery-1.js"></script>
<script src="<?php echo $this->webroot?>Croogo/js/jquery/bootstrap.js"></script>
<script src="<?php echo $this->webroot?>Croogo/js/autocomplete/jquery.min.js"></script>
<script src="<?php echo $this->webroot?>Croogo/js/autocomplete/bootstrap.min.js"></script>