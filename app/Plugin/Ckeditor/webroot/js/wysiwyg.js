Croogo.Wysiwyg.Ckeditor = {

	presets: {

		basic: {
			filebrowserBrowseUrl: null,
			filebrowserImageBrowseUrl: null,
			toolbar: [
				{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Format', 'Bold', 'Italic' ] },
				{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote' ] },
				{ name: 'links', items: [ 'Link', 'Unlink', 'Image' ] }
			],
			removeDialogTabs: 'image:advanced;link:target;link:advanced'
		},

		standard: {
			allowedContent: true,
			toolbar: [
				{ name: 'clipboard', groups: [ 'clipboard', 'undo' ], items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
				{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker' ], items: [ 'Scayt' ] },
				{ name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
				{ name: 'insert', items: [ 'Image', 'Table', 'HorizontalRule', 'SpecialChar' ] },
				{ name: 'tools', items: [ 'Maximize' ] },
				{ name: 'document', groups: [ 'mode', 'document', 'doctools' ], items: [ 'Source' ] },
				{ name: 'others', items: [ '-' ] },
				'/',
				{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Strike', '-', 'RemoveFormat' ] },
				{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote' ] },
				{ name: 'styles', items: [ 'Styles', 'Format' ] },
				{ name: 'about', items: [ 'About' ] }
			]
		},

		full: {
			allowedContent: true,
			toolbarGroups: [
				{ name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
				{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker' ] },
				{ name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
				'/',
				{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
				{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ] },
				{ name: 'links' },
				{ name: 'insert' },
				'/',
				{ name: 'styles' },
				{ name: 'colors' },
				{ name: 'tools' },
				{ name: 'others' },
				{ name: 'about' }
			]
		}
	},

	setup: function(el, config) {
		var preset = null;
		var defaults = {
			filebrowserBrowseUrl: Croogo.Wysiwyg.attachmentsPath,
			filebrowserImageBrowseUrl: Croogo.Wysiwyg.attachmentsPath
		};
		if (config.preset === false) {
			delete config.preset;
		} else {
			if (typeof config.preset == 'undefined') {
				config.preset = 'standard';
			}
			preset = Croogo.Wysiwyg.Ckeditor.presets[config.preset];
		}
		$.extend(defaults, preset);
		$.extend(defaults, config);
		$.extend(config, defaults);
		CKEDITOR.replace(el, config);
		CKEDITOR.on('instanceLoaded', function(evt) {
			CKEDITOR.skin.loadPart('croogo');
		});
	}

}

Croogo.Wysiwyg.choose = function(url, title, description) {
	var params = window.location.href.split('?')[1].split('&');
	var paramsObj = {};
	for (var i in params) {
		var param = params[i];
		var paramE = param.split('=');
		var k = paramE[0];
		var v = paramE[1];
		paramsObj[k] = v;
	}

	if (typeof paramsObj['CKEditorFuncNum'] != 'undefined') {
		window.top.opener.CKEDITOR.tools.callFunction(paramsObj['CKEditorFuncNum'], Croogo.Wysiwyg.uploadsPath + url);
		window.top.close();
	}
}