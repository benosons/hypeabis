<!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" lang="en-US">
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" lang="en-US">
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html lang="en-US">
<!--<![endif]-->

	<head>
		<!-- Basic page needs
		============================================ -->
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<!-- Mobile specific metas
		============================================ -->
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<title>Bisnis Muda Uploader</title>
		
		<link rel="stylesheet" type="text/css" href="<?= base_url(); ?>files/backend/plugins/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>files/backend/plugins/font-awesome/css/font-awesome.css" />
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>files/backend/plugins/fileinput/css/fileinput.css" media="screen" />
    
    <!-- Main CSS -->
    <link rel="stylesheet" type="text/css" class="main-stylesheet" href="<?= base_url(); ?>files/backend/css/pages.css"/>
    
    <script type="text/javascript" src="<?= base_url(); ?>files/backend/plugins/jquery/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>files/backend/plugins/modernizr.custom.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>files/backend/plugins/jquery-ui/jquery-ui.min.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>files/backend/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>files/backend/plugins/fileinput/js/fileinput.js"></script>
	</head>

	<body>
    <div class="container">
      <div class="row">
        <div class="col-12">
          <h3 class="m-t-20 m-b-15 fw-600 text-heading-black">Upload File</h3>
          <?= $this->session->flashdata('message'); ?>
        </div>
        
        <div class="col-12">
          <div class="col-lg-12 bg-master-lighter padding-20 m-b-20 b-rad-lg">
            <?= form_open_multipart("uploader/uploadFile/" . $this->uri->segment(3) . "/?CKEditor=".$CKEditor."&CKEditorFuncNum=".$CKEditorFuncNum."&langCode=".$langCode,array('class' => 'form-horizontal')); ?>
              <div class="form-group">
                <div class="row">
                  <label class="col-md-12 control-label text-left">Pilih File</label>
                  <div class="col-md-12 col-xs-12">
                    <? if($this->uri->segment(3) == 'all'){ ?>
                      
                      <input type="file" class="file" name="content_file" data-show-upload="false" data-show-close="false" data-show-preview="false" data-allowed-file-extensions='["jpg", "jpeg", "png", "pdf", "doc", "docx", "xls", "xlsx", "ppt", "pptx", "txt", "rar", "zip", "tar", "gzip"]'/>
                      <p class="help-block"><small>Format file: jpg, png, gif, pdf, doc, docx, xls, xlsx, ppt, pptx, txt. Maximum size: 5MB.</small></p>
                      
                    <? } else if ($this->uri->segment(3) == 'image') { ?>
                    
                      <input type="file" class="file" name="content_file" data-show-upload="false" data-show-close="false" data-show-preview="false" data-allowed-file-extensions='["jpg", "png", "jpeg"]'/>
                      <p class="help-block"><small>Format file: jpg, jpeg, png &amp; gif. Maksimal 5MB.</small></p>
                      
                    <? } else {}?>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <div class="col-md-12 col-xs-12">
                    <input type="Submit" value="Upload" class="btn btn-complete" />
                    <input type="button" value="Cancel" class="btn" onclick="history.go(-1)" />
                  </div>
                </div>
              </div>
            <?= form_close(); ?>
          </div>
        </div>
      </div>
      
      <!-- Footer -->
      <footer>
        <div class="row">
          <div class="col-lg-12">
            <p>Copyright &copy; Hypeabis 2021</p>
          </div>
        </div>
      </footer>
    </div>
	</body>
</html>

	<script type="text/javascript">
		function returnToCkEditor(){
			window.opener.CKEDITOR.tools.callFunction(<?= $CKEditorFuncNum; ?>, "", "File not found");
			window.close();
		}
	</script>