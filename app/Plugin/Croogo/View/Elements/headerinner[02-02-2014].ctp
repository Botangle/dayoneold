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
                <div class="Header-Account-info"><span> Welcome <?php echo $this->
                    Session->read('Auth.User.username')?>.  </span> |  <?php
		
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
    $(function () {

        function split(val) {
            return val.split(/,\s*/);
        }

        function extractLast(term) {
            return split(term).pop();
        }

        var typeid = "";
        $("#searchvalue")
            // don't navigate away from the field on tab when selecting an item
                .bind("keydown", function (event) {
                    typeid = this.id;
                    if (event.keyCode === $.ui.keyCode.TAB &&
                            $(this).data("ui-autocomplete").menu.active) {
                        event.preventDefault();
                    }
                })
                .autocomplete({
                    source: function (request, response) {

                        ///var url = "/demos/botangle/subject/search";
                        var url = "/botangle/subject/search";

                        $.getJSON(url, {
                            term: extractLast(request.term)
                        }, response);
                    },
                    search: function () {
                        // custom minLength
                        var term = extractLast(this.value);
                        if (term.length < 2) {
                            return false;
                        }
                    },
                    focus: function () {
                        // prevent value inserted on focus
                        return false;
                    },
                    select: function (event, ui) {
                        var terms = split(this.value);
                        if (typeid == 'LessonTutor') {
                            jQuery("#" + typeid + "Value").val(ui.item.id)
                        }
                        // remove the current input
                        terms.pop();
                        // add the selected item
                        terms.push(ui.item.value);
                        // add placeholder to get the comma-and-space at the end
                        terms.push("");
                        this.value = terms.join(" ");
                        return false;
                    }
                });
    });
</script>