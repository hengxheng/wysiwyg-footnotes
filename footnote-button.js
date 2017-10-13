(function(){
	tinymce.PluginManager.add('hz_tc_button', function(editor, url){
		editor.addButton('hz_tc_button', {
			text: 'FOOTNOTE',
			icon: false,
			onclick: function(cc){
				editor.focus();
				editor.selection.setContent('[footnote* '+editor.selection.getContent()+']');
			}
		})
	});
})();