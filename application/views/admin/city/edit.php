<? $this->load->view('admin/city/sub_header'); ?>

<!-- START CONTAINER FLUID -->
<div class="container-fluid container-fixed-lg content-wrapper">
  <!-- BEGIN PlACE PAGE CONTENT HERE -->

  <div class="row">
    <div class="col-md-12">
    
      <?= $this->session->flashdata('message'); ?>
      
      <!-- START card -->
      <div class="card card-transparent">
        <div class="card-body">
        
          <h4 class="m-t-0 m-b-15 fw-600 text-heading-black">
            Edit City
          </h4>
          
          <div class="bg-master-lighter padding-20 m-b-20 b-rad-lg">
          
            <?= form_open("admin_city/saveEdit/" . $city[0]->id_city ,array('class' => 'form-horizontal', 'role' => 'form', 'id' => 'city_form', 'autocomplete' => 'off')); ?>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Province</label>
                  <div class="col-md-8 col-xs-12">
                    <select name="province" class="full-width select_withsearch">
                      <option value="">- choose province -</option>
                      <? foreach($province as $item){ ?>
                        <option value="<?= $item->id_province; ?>" <?= ($city[0]->id_province == $item->id_province ? 'selected' : ''); ?>><?= $item->province_name; ?></option>
                      <? } ?>
                    </select>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Type</label>
                  <div class="col-md-8 col-xs-12">
                    <select class="full-width select_nosearch" name="city_type">
                      <option value="Kabupaten" <?= $city[0]->city_type == 'Kabupaten' ? 'selected' : '' ?>>District (Kabupaten)</option>
                      <option value="Kota" <?= $city[0]->city_type == 'Kota' ? 'selected' : '' ?>>City (Kota)</option>
                    </select>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">City name</label>
                  <div class="col-md-8 col-xs-12">
                    <input class="form-control" type="text" name="city_name" value="<?= $city[0]->city_name; ?>"/>
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

<script type="text/javascript">
	$(document).ready(function() {
		$('#city_form').bootstrapValidator({
			message: 'This value is not valid',
			feedbackIcons: {
				valid: 'glyphicon glyphicon-ok',
				invalid: 'glyphicon glyphicon-remove',
				validating: 'glyphicon glyphicon-refresh'
			},
			fields: {
				title: {
					group: '.col-md-8',
					validators: {
						notEmpty: {
							message: 'Title is required and cannot be empty'
						}
					}
				},
				city: {
					group: '.col-md-10',
					validators: {
						notEmpty: {
							message: 'City is required and cannot be empty'
						}
					}
				}
			}
		});
	});
</script>