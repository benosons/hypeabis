/**
 * @license Copyright (c) 2003-2018, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function( config ) {
  var base_url = "";
	config.language = 'en';
  
  config.filebrowserBrowseUrl = base_url + '/uploader/upload/all/';
	config.filebrowserImageBrowseUrl = base_url + '/uploader/upload/image/';
	config.filebrowserUploadUrl = base_url + '/uploader/direct/all/';
	config.filebrowserImageUploadUrl = base_url + '/uploader/direct/image/';
	config.filebrowserWindowWidth = '820';
  config.filebrowserWindowHeight = '470';
	
  config.autoParagraph = false;
	config.allowedContent  = true;
	config.enterMode = CKEDITOR.ENTER_BR;
	config.toolbar = [
		{name: 'tools', items: [ 'Maximize', 'Source' ] },
		{name: 'undo', items: ['Undo', 'Redo']},
		{name: 'colors', items: [ 'TextColor' ] },
		{name: 'basicstyles', items: [ 'FontSize', 'Bold', 'Italic', 'Underline'] },
		{name: 'links', items: [ 'Link', 'Unlink' ] },
		{name: 'paragraph', items: [ 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'] },
		{name: 'paragraph', items: [ 'NumberedList', 'BulletedList'] },
		{name: 'insert', items: [ 'imagebinary','btgrid'] },
		{name: 'styles', items: [ 'Format'] },
	];
	
	config.extraPlugins = 'imagebinary,btgrid, youtube';
  
  /* youtube config */
  config.youtube_height = '480';
  config.youtube_responsive = true;
  config.youtube_related = true;
  config.youtube_controls = true;
};

CKEDITOR.on( 'dialogDefinition', function( ev ) {
  var dialogName = ev.data.name;
  var dialogDefinition = ev.data.definition;

  if ( dialogName == 'table' ) {
    var info = dialogDefinition.getContents( 'info' );
    info.get( 'txtWidth' )[ 'default' ] = '100%';       // Set default width to 100%
    info.get( 'txtBorder' )[ 'default' ] = '0';         // Set default border to 0
    
    var addCssClass = dialogDefinition.getContents('advanced').get('advCSSClasses');
    addCssClass['default'] = 'table';
  }
});
