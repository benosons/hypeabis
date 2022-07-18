<? $this->load->view('dev/global/sub_header'); ?>

<!-- START CONTAINER FLUID -->
<div class="container-fluid container-fixed-lg content-wrapper">
  <!-- BEGIN PlACE PAGE CONTENT HERE -->

  <div class="row">
    <div class="col-md-12">
    
      <?= $this->session->flashdata('message'); ?>
      
      <!-- START card -->
      <div class="card card-transparent">
        <div class="card-body">
          
          <!-- Start Search Bar --> 
          <div class="bg-master-lighter padding-20 m-b-20 b-rad-lg">
          
            <script type="text/javascript" src="https://maps.google.com/maps/api/js?key=<?= $this->config->item('goole_maps_key'); ?>"></script>
            <!-- script for showing map -->
            <script type="text/javascript">
              var geocoder = new google.maps.Geocoder();
              function updateMarkerPosition(latLng) {
                document.getElementById('latitude').value = latLng.lat();
                document.getElementById('longitude').value = latLng.lng();
              }
              function updateZoomLevel(zoomLevel) {
                document.getElementById('zoom_level').value = zoomLevel;
              }
              function initialize() {
                var latLng = new google.maps.LatLng(<?= $global[0]->latitude; ?>,<?= $global[0]->longitude; ?>);
                var map = new google.maps.Map(document.getElementById('mapCanvas'), {
                zoom: <?= $global[0]->zoom_level; ?>,
                center: latLng,
                mapTypeId: google.maps.MapTypeId.ROADMAP
                });
                var marker = new google.maps.Marker({
                position: latLng,
                title: '<?= $global[0]->website_name; ?>',
                map: map,
                draggable: true,
                icon: new google.maps.MarkerImage(
                  "<?= base_url(); ?>files/backend/img/point.png", // reference from your base
                  new google.maps.Size(50, 40), // size of image to capture
                  new google.maps.Point(0, 0), // start reference point on image (upper left)
                  new google.maps.Point(13, 40), // point on image to center on latlng (scaled)
                  new google.maps.Size(50, 40) // actual size on map
                )
                });
                updateMarkerPosition(latLng);
                updateZoomLevel(map.getZoom());
                google.maps.event.addListener(marker, 'drag', function() {
                updateMarkerPosition(marker.getPosition());
                });
                google.maps.event.addListener(map, 'zoom_changed', function() {
                updateZoomLevel(map.getZoom());
                });
              }
              // Onload handler to fire off the app.
              google.maps.event.addDomListener(window, 'load', initialize);
            </script>
            
            <div class="row">
              <div class="col-md-12">
                <div id="mapCanvas" class="thumbnail" style="width:100%;height:250px;"></div>
              </div>
            </div>
          
            <?= form_open_multipart("dev_global/updateGlobal",array('class' => 'form-horizontal', 'role' => 'form', 'id' => 'global_setting_form', 'autocomplete' => 'off')); ?>
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Location:</label>
                  <div class="col-md-3">
                    <input type="text" class="form-control" id="latitude" name="latitude" value="<?= $global[0]->latitude; ?>" readonly="yes"/>
                  </div>
                  <div class="col-md-3">
                    <input type="text" class="form-control" id="longitude" name="longitude" value="<?= $global[0]->longitude; ?>" readonly="yes"/>
                  </div>
                  <div class="col-md-2">
                    <input type="text" class="form-control" id="zoom_level" name="zoom_level" value="<?= $global[0]->zoom_level; ?>" readonly="yes"/>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Logo width (px):</label>
                  <div class="col-md-3">
                    <input class="form-control" type="number" name="logo_width" required="yes" value="<?= $global[0]->logo_width; ?>"/>
                  </div>
                  <label class="col-md-2 control-label text-right sm-text-left">Logo height (px):</label>
                  <div class="col-md-3">
                    <input class="form-control" type="number" name="logo_height" required="yes" value="<?= $global[0]->logo_height; ?>"/>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Logo file:</label>
                  <div class="col-md-5">
                    <? if(strlen(trim($global[0]->logo)) > 0){ ?>
                      <div class="file-preview">
                        <div class="file-preview-thumbnails">
                        <div class="file-preview-frame">
                           <img src="<?= base_url(); ?>assets/logo/<?= $global[0]->logo; ?>" class="file-preview-image" title="<?= $global[0]->logo; ?>" width="auto" style="max-height:40px">
                        </div>
                        </div>
                         <div class="clearfix"></div><div class="file-preview-status text-center text-complete"></div>
                      </div>
                    <? } ?>
                    <input type="file" class="file" name="file_logo" id="file_logo" data-show-upload="false" data-show-close="false" data-show-preview="false" data-allowed-file-extensions='["jpg", "jpeg", "png"]' />
                  </div>
                  <div class="col-md-5">
                    <? if(strlen(trim($global[0]->logo_white)) > 0){ ?>
                      <div class="file-preview">
                        <div class="file-preview-thumbnails">
                        <div class="file-preview-frame">
                           <img src="<?= base_url(); ?>assets/logo/<?= $global[0]->logo_white; ?>" class="file-preview-image" title="<?= $global[0]->logo_white; ?>" width="auto" style="max-height:40px">
                        </div>
                        </div>
                         <div class="clearfix"></div><div class="file-preview-status text-center text-complete"></div>
                      </div>
                    <? } ?>
                    <input type="file" class="file" name="file_logo_white" id="file_logo_white" data-show-upload="false" data-show-close="false" data-show-preview="false" data-allowed-file-extensions='["jpg", "jpeg", "png"]' />
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Cover file:</label>
                  <div class="col-md-5">
                    <? if(strlen(trim($global[0]->cover)) > 0){ ?>
                      <div class="file-preview">
                        <div class="file-preview-thumbnails">
                        <div class="file-preview-frame">
                           <img src="<?= base_url(); ?>assets/cover/<?= $global[0]->cover; ?>" class="file-preview-image" title="<?= $global[0]->cover; ?>" width="auto" style="max-height:75px">
                        </div>
                        </div>
                         <div class="clearfix"></div>   <div class="file-preview-status text-center text-success"></div>
                      </div>
                    <? } ?>
                    <input type="file" class="file" name="file_cover" id="file_cover" data-show-upload="false" data-show-close="false" data-show-preview="false" data-allowed-file-extensions='["jpg", "jpeg", "png", "gif"]' />
                    <p class="hint-text"><small>*(Size Recommendation: <?= ($global[0]->logo_width > 0 ? $global[0]->logo_width : $this->logo_width); ?>px x <?= ($global[0]->logo_height > 0 ? $global[0]->logo_height : $this->logo_height); ?>px.)</small></p>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Tipe Layout Homepage</label>
                  <div class="col-md-8">
                    <div class="radio radio-complete">
                      <input type="radio" value="1" name="home_layout_type" id="home-layout-type-1" <?php echo $global[0]->home_layout_type === '1' ? 'checked' : '' ?>>
                      <label for="home-layout-type-1">Multi Item Main Slider</label>
                      <input type="radio" value="2" name="home_layout_type" id="home-layout-type-2" <?php echo $global[0]->home_layout_type === '2' ? 'checked' : '' ?>>
                      <label for="home-layout-type-2">Single Item Main Slider</label>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Website name:</label>
                  <div class="col-md-8">
                    <input class="form-control" type="text" name="website_name" value="<?= $global[0]->website_name; ?>" />
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Tagline:</label>
                  <div class="col-md-8">
                    <input class="form-control" type="text" name="tagline" value="<?= $global[0]->tagline; ?>" />
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">URL:</label>
                  <div class="col-md-8">
                    <input class="form-control" type="text" name="url" value="<?= $global[0]->url; ?>" />
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Address:</label>
                  <div class="col-md-8">
                    <textarea name="address" class="form-control" rows="3"><?= $global[0]->address; ?></textarea>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Email:</label>
                  <div class="col-md-6">
                    <input class="form-control" type="text" name="email" value="<?= $global[0]->email; ?>" />
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Phone:</label>
                  <div class="col-md-6">
                    <input class="form-control" type="text" name="phone1" value="<?= $global[0]->phone1; ?>" />
                  </div>
                </div>
              </div>
              
              <div class="form-group" style="display:none;">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Phone 2:</label>
                  <div class="col-md-6">
                    <input class="form-control" type="text" name="phone2" value="<?= $global[0]->phone2; ?>" />
                  </div>
                </div>
              </div>
              
              <div class="form-group" style="display:none;">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Fax:</label>
                  <div class="col-md-6">
                    <input class="form-control" type="text" name="fax" value="<?= $global[0]->fax; ?>" />
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Facebook:</label>
                  <div class="col-md-8">
                    <input class="form-control" type="text" name="facebook" value="<?= $global[0]->facebook; ?>" />
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Twitter:</label>
                  <div class="col-md-8">
                    <input class="form-control" type="text" name="twitter" value="<?= $global[0]->twitter; ?>" />
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Youtube:</label>
                  <div class="col-md-8">
                    <input class="form-control" type="text" name="youtube" value="<?= $global[0]->youtube; ?>" />
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Instagram:</label>
                  <div class="col-md-8">
                    <input class="form-control" type="text" name="instagram" value="<?= $global[0]->instagram; ?>" />
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Linkedin:</label>
                  <div class="col-md-8">
                    <input class="form-control" type="text" name="linkedin" value="<?= $global[0]->linkedin; ?>" />
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 hidden-xs control-label text-right sm-text-left">&nbsp;</label>
                  <div class="col-md-9">
                    <button class="btn btn-complete sm-m-b-10" type="submit">Update setting</button>
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
		$('#global_setting_form').bootstrapValidator({
			message: 'This value is not valid',
			feedbackIcons: {
				valid: 'glyphicon glyphicon-ok',
				invalid: 'glyphicon glyphicon-remove',
				validating: 'glyphicon glyphicon-refresh'
			},
			fields: {
				website_name: {
					group: '.col-md-8',
					validators: {
						notEmpty: {
							message: 'Website name is required and cannot be empty'
						}
					}
				},
				url: {
					group: '.col-md-8',
					validators: {
						notEmpty: {
							message: 'URL is required and cannot be empty'
						}
					}
				},
				address: {
					group: '.col-md-8',
					validators: {
						notEmpty: {
							message: 'Address is required and cannot be empty'
						}
					}
				},
				email: {
					group: '.col-md-6',
					validators: {
						notEmpty: {
							message: 'Email is required and cannot be empty'
						},
						emailAddress: {
							message: 'This input is not a valid email address'
						}
					}
				},
				phone1: {
					group: '.col-md-6',
					validators: {
						notEmpty: {
							message: 'Phone is required and cannot be empty'
						}
					}
				}
			}
		});
	});
</script>
