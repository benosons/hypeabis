/*
*
* @author 		: Hengky Mulyono <hengkymulyono301@gmail.com>
* @copyright	: Binary Project 2017
* @copyright	: mail@binary-project.com
* @version		: Release: 1.0.0
* @link			: www.binary-project.com
* @contact		: 0822 3709 9004
*
*/
CKEDITOR.plugins.add( 'imagebinary', {
    icons: 'imagebinary',
    init: function( editor ) {
        editor.addCommand( 'imagebinary', new CKEDITOR.dialogCommand( 'imagebinaryDialog' ) );
        editor.ui.addButton( 'imagebinary', {
            label: 'Insert Image',
            command: 'imagebinary',
            toolbar: 'insert'
        });

        CKEDITOR.dialog.add( 'imagebinaryDialog', this.path + 'dialogs/imagebinary.js' );
	}
});