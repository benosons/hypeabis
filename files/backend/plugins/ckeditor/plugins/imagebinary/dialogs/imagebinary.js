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
CKEDITOR.dialog.add( 'imagebinaryDialog', function( editor ) {
    return {
        title: 'Upload Gambar',
        minWidth: 400,
        minHeight: 200,
        contents: [
          {
            id: 'tab-uploadimage',
            label: 'Basic Settings',
            elements: [
              {
                type: 'hbox',
                widths: [ '75%', '25%'],
                children: [
                  {
                    type: 'text',
                    id: 'txt_url',
                    label: 'URL gambar:',
                    validate: CKEDITOR.dialog.validate.notEmpty( "URL gambar tidak boleh kosong." ),
                    setup: function( element ) {
                      this.setValue( element.getAttribute( "src" ) );
                    },
                    commit: function( element ) {
                      element.setAttribute( "src", this.getValue() );
                    }
                  },
                  {
                    type: "button",
                    id: "browse",
                    style: "display:block;margin-top:20px;",
                    align: "center",
                    label: "Upload",
                    hidden: !0,
                    filebrowser: {
                      action: "Browse",
                      target: "tab-uploadimage:txt_url",
                      url: editor.config.filebrowserImageBrowseUrl
                    }
                  }
                ]
              },
              {
                type: 'hbox',
                widths: [ '70%', '30%'],
                children: [
                  {
                    type: 'text',
                    id: 'txt_caption',
                    label: 'Caption:',
                    validate: CKEDITOR.dialog.validate.notEmpty( "input caption atau keterangan gambar." ),
                    'default' : '',
                    setup: function( element ) {
                      this.setValue( element.getAttribute( "data-caption" ) );
                    },
                    commit: function( element ) {
                      element.setAttribute( "data-caption", this.getValue());
                      element.setAttribute( "alt", this.getValue());
                      element.setAttribute( "title", this.getValue());
                    }
                  }
                ]
              },
              {
                type: 'hbox',
                widths: [ '70%', '30%'],
                children: [
                  {
                    type: 'text',
                    id: 'txt_width',
                    label: 'Lebar maksimal gambar (px / %):',
                    // validate: CKEDITOR.dialog.validate.notEmpty( "Width field cannot be empty." ),
                    'default' : 'auto',
                    setup: function( element ) {
                      this.setValue( element.getAttribute( "data-max-width" ) );
                    },
                    commit: function( element ) {
                      element.setAttribute( "data-max-width", this.getValue());
                      element.setAttribute( "style", "padding:0px 20px 10px 20px;max-width:" + this.getValue() + ";" );
                    }
                  }
                ]
              },
              {
                type: 'select',
                id: 'select_type',
                label: 'Alignment:',
                items: [['None (not set)', ''], ['Left','left'], ['Right','right'], ['Center','middle']],
                default: 'middle',
                setup: function( element ) {
                  this.setValue( element.getAttribute( "align" ) );
                },
                commit: function( element ) {
                  element.setAttribute( "align", this.getValue() );
                }
              }
            ]
          }
        ],
        onOk: function() {
          var dialog = this;
          var	img = dialog.element;
          var image_url = dialog.getValueOf( 'tab-uploadimage', 'txt_url' );
          var image_caption = dialog.getValueOf( 'tab-uploadimage', 'txt_caption' );
          var image_width = dialog.getValueOf( 'tab-uploadimage', 'txt_width' );
          var image_type = dialog.getValueOf( 'tab-uploadimage', 'select_type' );
          var element_str = "";
          var image_width_clean = "";
          
          //validate image width..
          if(image_width.toLowerCase().indexOf("%") !== -1 || image_width.toLowerCase().indexOf("px") !== -1 ){
            image_width_clean = image_width.replace('%', '').replace('px', '');
            if(isNaN(image_width_clean)){
              image_width = "auto";
            }
          }
          else{
            image_width = "auto";
          }
          dialog.commitContent( img );
          
          if ( dialog.insertMode ){
            element_str = "";
            if (image_type == 'left' || image_type == 'right'){
              element_str += "<br/>";
              element_str += "<figure style='" + (image_width != 'auto' ? "max-width:" + image_width + ";" : "") + "float:" + image_type + ";margin:0px " + (image_type == "left" ? "20px" : "0px") + " 0px " + (image_type == "right" ? "20px" : "0px") + ";background:#ffffff;border:none;'>";
              element_str += "<img src='" + image_url + "' align='" + image_type + "' class='img img-responsive lazy' data-max-width='" + image_width + "' style='width:auto;max-width:100%;margin-bottom:5px;'  data-caption='" + image_caption + "'/>";
              element_str += "<figcaption style='text-align:center;font-size:12px;color:#888888;'>" + image_caption + "</figcaption>";
              element_str += "</figure>";
            }
            else{
              element_str += "<br/><div style='text-align:center;'>";
              element_str += "<p><img src='" + image_url + "' align='center' alt='" + image_caption + "' title='" + image_caption + "' class='img img-responsive lazy' data-max-width='" + image_width + "' data-caption='" + image_caption + "' style='padding:0px 20px 0px 20px;max-width:" + image_width + "'/></p>";
              element_str += "<p align='center' style='font-size:12px;color:#888888;margin-top:-10px;'>" + image_caption + "</p>";
              element_str += "</div><br/>";
            }
            editor.insertHtml(element_str);
          }
        },
        onShow: function() {
          var selection = editor.getSelection();
          var element = selection.getStartElement();
          var element_name = element.getName();

          if ( !element || element.getName() != 'img' ) {
            element = editor.document.createElement( 'img' );
            this.insertMode = true;
          }
          else{
            this.insertMode = false;
          }
          
          this.element = element;
                if ( !this.insertMode ){
                    this.setupContent( this.element );
          }
        }
    };
});