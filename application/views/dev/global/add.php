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
                var latLng = new google.maps.LatLng(-6.175291, 106.827057);
                var map = new google.maps.Map(document.getElementById('mapCanvas'), {
                zoom: 12,
                center: latLng,
                mapTypeId: google.maps.MapTypeId.ROADMAP
                });
                var marker = new google.maps.Marker({
                position: latLng,
                title: '',
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
          
            <?= form_open_multipart("dev_global/saveGlobal",array('class' => 'form-horizontal', 'role' => 'form', 'id' => 'global_setting_form', 'autocomplete' => 'off')); ?>
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Location:</label>
                  <div class="col-md-3">
                    <input type="text" class="form-control" id="latitude" name="latitude" value="-6.175291" readonly="yes"/>
                  </div>
                  <div class="col-md-3">
                    <input type="text" class="form-control" id="longitude" name="longitude" value="106.827057" readonly="yes"/>
                  </div>
                  <div class="col-md-2">
                    <input type="text" class="form-control" id="zoom_level" name="zoom_level" value="12" readonly="yes"/>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Logo width (px):</label>
                  <div class="col-md-3">
                    <input class="form-control" type="number" name="logo_width" required="yes"/>
                  </div>
                  <label class="col-md-2 control-label text-right sm-text-left">Logo height (px):</label>
                  <div class="col-md-3">
                    <input class="form-control" type="number" name="logo_height" required="yes"/>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Logo file:</label>
                  <div class="col-md-5">
                    <input type="file" class="file" name="file_logo" id="file_logo" data-show-upload="false" data-show-close="false" data-show-preview="false" data-allowed-file-extensions='["jpg", "jpeg", "png"]' />
                  </div>
                  <div class="col-md-5">
                    <input type="file" class="file" name="file_logo_white" id="file_logo_white" data-show-upload="false" data-show-close="false" data-show-preview="false" data-allowed-file-extensions='["jpg", "jpeg", "png"]' />
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Cover file:</label>
                  <div class="col-md-5">
                    <input type="file" class="file" name="file_cover" id="file_cover" data-show-upload="false" data-show-close="false" data-show-preview="false" data-allowed-file-extensions='["jpg", "jpeg", "png", "gif"]' />
                    <p class="hint-text"><small>*(Size Recommendation: <?= $this->cover_width; ?>px x <?= $this->cover_height; ?>px.)</small></p>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Website name:</label>
                  <div class="col-md-8">
                    <input class="form-control" type="text" name="website_name" />
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Tagline:</label>
                  <div class="col-md-8">
                    <input class="form-control" type="text" name="tagline" value="" />
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">URL:</label>
                  <div class="col-md-8">
                    <input class="form-control" type="text" name="url" value="" />
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Address:</label>
                  <div class="col-md-8">
                    <textarea name="address" class="form-control" rows="3"></textarea>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Email:</label>
                  <div class="col-md-6">
                    <input class="form-control" type="text" name="email" value="" />
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Phone:</label>
                  <div class="col-md-6">
                    <input class="form-control" type="text" name="phone1" value="" />
                  </div>
                </div>
              </div>
              
              <div class="form-group" style="display:none;">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Phone 2:</label>
                  <div class="col-md-6">
                    <input class="form-control" type="text" name="phone2" value="" />
                  </div>
                </div>
              </div>
              
              <div class="form-group" style="display:none;">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Fax:</label>
                  <div class="col-md-6">
                    <input class="form-control" type="text" name="fax" value="" />
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Facebook:</label>
                  <div class="col-md-8">
                    <input class="form-control" type="text" name="facebook" value="" />
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Twitter:</label>
                  <div class="col-md-8">
                    <input class="form-control" type="text" name="twitter" value="" />
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Youtube:</label>
                  <div class="col-md-8">
                    <input class="form-control" type="text" name="youtube" value="" />
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Instagram:</label>
                  <div class="col-md-8">
                    <input class="form-control" type="text" name="instagram" value="" />
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Linkedin:</label>
                  <div class="col-md-8">
                    <input class="form-control" type="text" name="linkedin" value="" />
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 hidden-xs control-label text-right sm-text-left">&nbsp;</label>
                  <div class="col-md-9">
                    <button class="btn btn-complete sm-m-b-10" type="submit">Submit setting</button>
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