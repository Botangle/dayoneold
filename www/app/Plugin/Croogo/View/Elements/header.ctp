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
		 
 
 
$(function() {
$.getJSON( "/subject/search",function(response){
	data = response;
 
 $( "#searchvalue" ).autocomplete({
minLength: 0,
source: data,
focus: function( event, ui ) {
$( "#searchvalue" ).val( ui.item.label );
return false;
},
select: function( event, ui ) { console.log(ui)
$( "#searchvalue" ).val( ui.item.label );
 
 
return false;
}
})
.data( "ui-autocomplete" )._renderItem = function( ul, item ) {
return $( "<li>" )
.append( "<a>" + item.label + "</a>" )
.appendTo( ul );
};
})
 });
 
	jQuery(function() {
		 
		function split( val ) {
			return val.split( /,\s*/ );
		}
		function extractLast( term ) { 
			return split( term ).pop();
		}
		var typeid = "";
		jQuery( "#searchvalue2" ) 
			// don't navigate away from the field on tab when selecting an item
			.bind( "keydown", function( event ) { typeid = this.id;
				if ( event.keyCode === jQuery.ui.keyCode.TAB &&
						$( this ).data( "ui-autocomplete" ).menu.active ) {
					event.preventDefault();
				}
			})
			.autocomplete({
				source: function( request, response ) {
				 
					var url = "/subject/search";
					 
					$.getJSON( url, {
						term: extractLast( request.term )
					}, response );
				},
				search: function() {
					// custom minLength
					var term = extractLast( this.value );
					if ( term.length < 2 ) {
						return false;
					}
				},
				focus: function() {
					// prevent value inserted on focus
					return false;
				},
				select: function( event, ui ) {  
					var terms = split( this.value );
					if(typeid=='LessonTutor'){
						jQuery("#"+typeid+"Value").val(ui.item.id)
					}
					// remove the current input
					terms.pop();
					// add the selected item
					terms.push( ui.item.value );
					// add placeholder to get the comma-and-space at the end
					terms.push( "" );
					this.value = terms.join( " " );
					return false;
				}
			});
	});
	</script>


<header id="Bannerblock">
  <div class="container text-center">
    <h1>What do you need help with?</h1>
    <div class="Searchblock row-fluid">
      
	  
	  <form method="post" action="<?php echo $this->webroot?>user/search" id="searchuser" class="form-search">
        <div class="Search-main1" id="home-search">
		<input name="searchvalue" id="searchvalue" type="text" placeholder="Example: Chemistry, Maths etc" />
		  
        </div>
        <div class="Search-main1-btn">
          <button type="submit" class="btn search-main-btn">Search</button>
        </div>
       </form>
    </div>
    <div class="joinus">
      <div class="title"> Join Us </div>
      <div class=" row-fluid join-btn-block">
        <div class="span6 joinus-button1"> 
		<?php
		echo $this->Html->link(
    __('Become a Student'),	'/registration/student'
     ,
	array('class'=>'join-btn','title'=>__('Become a Student') )
);
	   ?>
	   </div>
        <div class="span6 joinus-button1"> 
		<?php
		echo $this->Html->link(
    __('Become an Expert'),	'/registration/tutor'
     ,
	array('class'=>'join-btn','title'=>__('Become an Expert') )
);
	   ?>
	  
		
		  </div>
      </div>
    </div>
  </div>
</header>