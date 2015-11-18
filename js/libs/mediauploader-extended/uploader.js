(function( $ ) {
/* global wp, console */
	$(function() {

		var workflows = {};

  		var model_tag = '';
  		var model_tag_class = '';
  		var img_alignment = '';
  		var linkTo = '';

  		// we are extending/hijacking  wp.media.editor

  		wp.media.editor.open = function( id, options ){
			/* DEFAULT WP */
			var workflow;

			options = options || {};

			id = this.id( id );
			this.activeEditor = id;

			workflow = this.get( id );

			// Redo workflow if state has changed
			if ( ! workflow || ( workflow.options && options.state !== workflow.options.state ) ) {
			workflow = this.add( id, options );
			}

			/* OUR CODE */
			setTimeout(function() {
				console.log('test1');
				$('.media-button-insert').on('mousedown' , function (event){
					model_tag = $('.media-sidebar #model_tag option:selected').val();
					model_tag_class = $('.media-sidebar .model_tag_class').val();
					img_alignment = $('.attachment-display-settings .alignment option:selected').val();
					linkTo = $('.attachment-display-settings .link-to option:selected').val();
				});
			}, 150);

			/* DEFAULT WP */
			return workflow.open();
  		};


		wp.media.editor.insert = function(html){
			/* OUR CODE */
			console.log(linkTo);
			//if(model_tag == 1 && linkTo == 'none'){ // only add if 'link-to' is 'none'
			if(model_tag == 1){
				var _arg = [];

				a = arguments[0];
				a = a.split("\n");
				a = a.filter(Boolean);

				$(a).each(function(index, el) {
					imgAlignment = 'align'+img_alignment;
					imgW = $(el).attr('width');
					_arg.push('<span style="width:'+imgW+'px" class="'+ model_tag_class +' '+ imgAlignment +'">' + el + '</span>');
				});
				_arg = _arg.join("\n\n");
				_arg = [_arg];
				var arguments = _arg;
			}

			/* DEFAULT WP */
			var editor, wpActiveEditor,
			   hasTinymce = ! _.isUndefined( window.tinymce ),
			   hasQuicktags = ! _.isUndefined( window.QTags );

			if ( this.activeEditor ) {
			   wpActiveEditor = window.wpActiveEditor = this.activeEditor;
			} else {
			   wpActiveEditor = window.wpActiveEditor;
			}

			// Delegate to the global `send_to_editor` if it exists.
			// This attempts to play nice with any themes/plugins that have
			// overridden the insert functionality.
			if ( window.send_to_editor ) {
			   return window.send_to_editor.apply( this, arguments );
			}

			if ( ! wpActiveEditor ) {
			   if ( hasTinymce && tinymce.activeEditor ) {
			       editor = tinymce.activeEditor;
			       wpActiveEditor = window.wpActiveEditor = editor.id;
			   } else if ( ! hasQuicktags ) {
			       return false;
			   }
			} else if ( hasTinymce ) {
			   editor = tinymce.get( wpActiveEditor );
			}

			if ( editor && ! editor.isHidden() ) {
			   editor.execCommand( 'mceInsertContent', false, html );
			} else if ( hasQuicktags ) {
			   QTags.insertContent( html );
			} else {
			   document.getElementById( wpActiveEditor ).value += html;
			}

			// If the old thickbox remove function exists, call it in case
			// a theme/plugin overloaded it.
			if ( window.tb_remove ) {
			   try { window.tb_remove(); } catch( e ) {}
			}
		}

	});

})( jQuery );
console.log('we are GOOD!');