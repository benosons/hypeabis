<? $this->load->view('admin/slider/sub_header_manage'); ?>

<!-- START CONTAINER FLUID -->
<div class="container-fluid container-fixed-lg content-wrapper">
  <!-- BEGIN PlACE PAGE CONTENT HERE -->

  <div class="row">
    <div class="col-md-12">
    
      <!-- START card -->
      <div class="card card-transparent">
        <div class="card-body">
        
          <h4 class="m-t-0 m-b-15 fw-600 text-heading-black">
            Add Slide
          </h4>
          
          <?= $this->session->flashdata('message'); ?>
          
          <div class="bg-master-lighter padding-20 m-b-20 b-rad-lg">
          
            <?= form_open_multipart("admin_slider/saveAddContent/" . $slider[0]->id_slider ,array('class' => 'form-horizontal', 'role' => 'form', 'id' => 'slider_form', 'autocomplete' => 'off')); ?>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Picture</label>
                  <div class="col-md-8 col-xs-12">
                    <input type="file" class="file" name="slider_pic" id="slider_pic" data-show-upload="false" data-show-close="false" data-show-preview="false" data-allowed-file-extensions='["jpg", "png"]'/>
                    <p class="hint-text"><small>*(Size Recommendation: <?= $slider[0]->width; ?>px x <?= $slider[0]->height; ?>px.)</small></p>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Slide text 1 (English)</label>
                  <div class="col-md-8 col-xs-12">
                    <input class="form-control" type="text" name="text1"/>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Slide text 1 (Bahasa)</label>
                  <div class="col-md-8 col-xs-12">
                    <input class="form-control" type="text" name="text1_lang1"/>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Slide text 2 (English)</label>
                  <div class="col-md-10 col-xs-12">
                    <textarea class="form-control" name="text2" rows="4"></textarea>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Slide text 2 (Bahasa)</label>
                  <div class="col-md-10 col-xs-12">
                    <textarea class="form-control" name="text2_lang1" rows="4"></textarea>
                  </div>
                </div>
              </div>
              
              <div class="form-group" style="display:none;">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Slide text 3</label>
                  <div class="col-md-8 col-xs-12">
                    <input class="form-control" type="text" name="text3"/>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Redirect URL</label>
                  <div class="col-md-8 col-xs-12">
                    <input class="form-control" type="text" name="redirect_url"/>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Button text</label>
                  <div class="col-md-6 col-xs-12">
                    <input class="form-control" type="text" name="button_text"/>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Order</label>
                  <div class="col-md-2 col-xs-12">
                    <input class="form-control" type="number" name="slider_order" value="99"/>
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