var currentdate = new Date();
	var y = currentdate.getFullYear();
	var m = currentdate.getMonth()+1;
	var d = currentdate.getDate();
	if(m < 9){
		m="0"+m;
	}if(d < 9){
		d="0"+d;
	}
	var dd = y+"-"+m+"-"+d;
	 
(function($) {
	 
	"use strict";

	var options = {
		events_source: Croogo.basePath+'users/calandarevents',
		view: 'month',
		tmpl_path: Croogo.basePath+'croogo/tmpls/',
		tmpl_cache: false,
		day: dd,
		onAfterEventsLoad: function(events) {
			if(!events) {
				return;
			}
			var list = $('#eventlist');
			list.html('');

			$.each(events, function(key, val) {
				$(document.createElement('li'))
					.html('<a href="' + val.url + '">' + val.title + '</a>')
					.appendTo(list);
			});
		},
		onAfterViewLoad: function(view) {
			$('.page-header h3').text(this.getTitle());
			$('.btn-group button').removeClass('active');
			$('button[data-calendar-view="' + view + '"]').addClass('active');
		},
		classes: {
			months: {
				general: 'label'
			}
		}
	};
	
	var calendar = $('#calendar').calendar(options);

	$('.btn-group button[data-calendar-nav]').each(function() { 
		var $this = $(this);
		$this.click(function() {
			calendar.navigate($this.data('calendar-nav'));
		});
	});

	$('.btn-group button[data-calendar-view]').each(function() {
		var $this = $(this);
		$this.click(function() {
			calendar.view($this.data('calendar-view'));
		});
	});

	$('#first_day').change(function(){
		var value = $(this).val();
		value = value.length ? parseInt(value) : null;
		calendar.setOptions({first_day: value});
		calendar.view();
	});

	$('#language').change(function(){
		calendar.setLanguage($(this).val());
		calendar.view();
	});

	$('#events-in-modal').change(function(){
		var val = $(this).is(':checked') ? $(this).val() : null;
		calendar.setOptions({modal: val});
	});
	$('#events-modal .modal-header, #events-modal .modal-footer').click(function(e){
		//e.preventDefault();
		//e.stopPropagation();
	});
}(jQuery));