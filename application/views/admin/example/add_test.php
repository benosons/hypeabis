<? $this->load->view('admin/example/sub_header'); ?>

<!-- START CONTAINER FLUID -->
<div class="container-fluid container-fixed-lg content-wrapper">
  <!-- BEGIN PlACE PAGE CONTENT HERE -->

  <div class="row">
    <div class="col-md-12">
    
      <!-- START card -->
      <div class="card card-transparent">
        <div class="card-body">
        
          <h4 class="m-t-0 m-b-15 fw-600 text-heading-black">
            Add Example
          </h4>
          
          <?= $this->session->flashdata('message'); ?>
          
          <div class="bg-master-lighter padding-20 m-b-20 b-rad-lg">
          
            <?= form_open_multipart("admin_example/saveAddTest",array('class' => 'form-horizontal', 'role' => 'form', 'id' => 'example_form', 'autocomplete' => 'off')); ?>
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">File upload (non picture)</label>
                  <div class="col-md-8 col-xs-12">
                    <?
                      $allowed_format = explode('|', $this->allowed_format);
                      $allowed_format_att = "";
                      $allowed_format_str = "";
                      foreach($allowed_format as $index => $format){
                        $allowed_format_att .= ($index > 0 ? ', ' : '');
                        $allowed_format_str .= ($index > 0 ? ', ' : '');
                        $allowed_format_att .= '"' . $format. '"';
                        $allowed_format_str .= $format;
                      }
                    ?>
                    <input type="file" class="file" name="example_file" id="example_file" data-show-upload="false" data-show-close="false" data-show-preview="false" data-allowed-file-extensions='[<?= $allowed_format_att; ?>]'/>
                    <p class="hint-text"><small>*(Allowed format: <?= $allowed_format_str; ?>. Leave this field blank if you don't want to upload file)</small></p>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 hidden-xs control-label text-right sm-text-left">&nbsp;</label>
                  <div class="col-md-9">
                    <button class="btn btn-complete sm-m-b-10" type="submit">Submit</button>
                    <button class="btn btn-default sm-m-b-10" onclick="history.go(-1)"><i class="fa fa-chevron-circle-left"></i> Back</button>
                  </div>
                </div>
              </div>
            <?= form_close(); ?>
            
          </div>

        </div>
      </div>
      <!-- END CARD -->
    </div>
  </div>
  
  <!-- END PLACE PAGE CONTENT HERE -->
</div>
<!-- END CONTAINER FLUID -->
