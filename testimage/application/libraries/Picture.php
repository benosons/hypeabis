<?php if ( ! defined("BASEPATH")) exit("No direct script access allowed");
 
class Picture{
	
  function __construct(){
    ini_set('memory_limit', '-1');
  }
	
	public function createThumb($image_path, $width = 0, $height = 0) {
		//Get the Codeigniter object by reference
		$CI = & get_instance();
		   
		//The new generated filename we want
		$fileinfo = pathinfo($image_path);
		$new_image_path = $fileinfo['dirname'] . '/thumb/' . str_replace('Original_', '', preg_replace("/[^a-zA-Z0-9]+/", "", $fileinfo['filename'])) .'_thumb'. '.' . $fileinfo['extension'];
		$new_image_name = str_replace('Original_', '', preg_replace("/[^a-zA-Z0-9]+/", "", $fileinfo['filename'])) .'_thumb'. '.' . $fileinfo['extension'];
		
		// harus image baru. tidak boleh ada nama yang sama sebelumnya
		if ((! file_exists($new_image_path)) || filemtime($new_image_path) < filemtime($image_path)) {
			$CI->load->library('image_lib');
		   
			//The original sizes
			$original_size = getimagesize($image_path);
			$original_width = $original_size[0];
			$original_height = $original_size[1];
			$ratio = $original_width / $original_height;
		   
			//The requested sizes
			$requested_width = $width;
			$requested_height = $height;
		   
			//Initialising
			$new_width = 0;
			$new_height = 0;
		   
			//Calculations
			if ($requested_width > $requested_height) {
				$new_width = $requested_width;
				$new_height = $new_width / $ratio;
				if ($requested_height == 0)
					$requested_height = $new_height;
			   
				if ($new_height < $requested_height) {
					$new_height = $requested_height;
					$new_width = $new_height * $ratio;
				}
		   
			}
			else {
				$new_height = $requested_height;
				$new_width = $new_height * $ratio;
				if ($requested_width == 0)
					$requested_width = $new_width;
			   
				if ($new_width < $requested_width) {
					$new_width = $requested_width;
					$new_height = $new_width / $ratio;
				}
			}
		   
			$new_width = ceil($new_width);
			$new_height = ceil($new_height);
		   
			//Resizing
			$config = array();
			$config['image_library'] = 'gd2';
			$config['source_image'] = $image_path;
			$config['new_image'] = $new_image_path;
			$config['maintain_ratio'] = true;
			$config['height'] = $new_height;
			$config['width'] = $new_width;
			$CI->image_lib->initialize($config);
			$CI->image_lib->resize();
			$CI->image_lib->clear();
		   
			//Crop if both width and height are not zero
			if (($width != 0) && ($height != 0)) {
				$x_axis = floor(($new_width - $width) / 2);
				$y_axis = floor(($new_height - $height) / 2);
			   
				//Cropping
				$config = array();
				$config['source_image'] = $new_image_path;
				$config['maintain_ratio'] = FALSE;
				$config['new_image'] = $new_image_path;
				$config['width'] = $width;
				$config['height'] = $height;
				$config['x_axis'] = $x_axis;
				$config['y_axis'] = $y_axis;
				$CI->image_lib->initialize($config);
				$CI->image_lib->crop();
				$CI->image_lib->clear();
			}
		}
		return $new_image_name;
	}

  public function createThumbWithRatio($image_path, $width = 0, $height = 0, $new_image = true)
  {
    //Get the Codeigniter object by reference
    $CI = &get_instance();

    //The new generated filename we want
    $fileinfo = pathinfo($image_path);
    $new_image_path = $fileinfo['dirname'] . '/thumb/' . str_replace('Original', '', preg_replace("/[^a-zA-Z0-9]+/", "", $fileinfo['filename'])) . '_thumb.' . $fileinfo['extension'];
    $new_image_name = str_replace('Original', '', preg_replace("/[^a-zA-Z0-9]+/", "", $fileinfo['filename'])) . '_thumb.' . $fileinfo['extension'];

    //Harus image baru. tidak boleh ada nama file yg sama sebelumnya, kecuali new_image di set false
    if ((!file_exists($new_image_path)) || filemtime($new_image_path) < filemtime($image_path) || $new_image == false) {
      $CI->load->library('image_lib');

      //The original sizes
      $original_size = getimagesize($image_path);
      $original_width = $original_size[0];
      $original_height = $original_size[1];
      $ratio = $original_width / $original_height;

      //The requested sizes
      $requested_width = $width;
      $requested_height = $height;

      //Initialising
      $new_width = 0;
      $new_height = 0;

      //Calculations
      if ($requested_width > $requested_height) { //landscape
        $new_width = $requested_width;
        $new_height = $new_width / $ratio;
        if ($requested_height == 0)
          $requested_height = $new_height;

        if ($new_height < $requested_height) {
          $new_height = $requested_height;
          $new_width = $new_height * $ratio;
        }
      } else { //potrait
        $new_height = $requested_height;
        $new_width = $new_height * $ratio;
        if ($requested_width == 0)
          $requested_width = $new_width;

        if ($new_width < $requested_width) {
          $new_width = $requested_width;
          $new_height = $new_width / $ratio;
        }
      }

      $new_width = ceil($new_width);
      $new_height = ceil($new_height);

      //Resizing
      $config = array();
      $config['image_library'] = 'gd2';
      $config['source_image'] = $image_path;
      if ($new_image) {
        $config['new_image'] = $new_image_path;
      }
      $config['maintain_ratio'] = true;
      $config['height'] = $new_height;
      $config['width'] = $new_width;

      $CI->image_lib->initialize($config);
      $CI->image_lib->resize();
      $CI->image_lib->clear();
    }
    return $new_image_name;
  }
  
  public function createThumbSquare($image_path, $width = 0, $height = 0){
    //Get the Codeigniter object by reference
		$CI = & get_instance();
		   
		//The new generated filename we want
		$fileinfo = pathinfo($image_path);
		$new_image_path = $fileinfo['dirname'] . '/thumb/' . str_replace('Original_', '', preg_replace("/[^a-zA-Z0-9]+/", "", $fileinfo['filename'])) .'_square'. '.' . $fileinfo['extension'];
		$new_image_name = str_replace('Original_', '', preg_replace("/[^a-zA-Z0-9]+/", "", $fileinfo['filename'])) .'_square'. '.' . $fileinfo['extension'];
		
		// harus image baru. tidak boleh ada nama yang sama sebelumnya
		if ((! file_exists($new_image_path)) || filemtime($new_image_path) < filemtime($image_path)) {
			$CI->load->library('image_lib');
		   
			//The original sizes
			$original_size = getimagesize($image_path);
			$original_width = $original_size[0];
			$original_height = $original_size[1];
			$ratio = $original_width / $original_height;
		   
			//The requested sizes
			$requested_width = $width;
			$requested_height = $height;
		   
			//Initialising
			$new_width = 0;
			$new_height = 0;
		   
			//Calculations
			if ($requested_width > $requested_height) {
				$new_width = $requested_width;
				$new_height = $new_width / $ratio;
				if ($requested_height == 0)
					$requested_height = $new_height;
			   
				if ($new_height < $requested_height) {
					$new_height = $requested_height;
					$new_width = $new_height * $ratio;
				}
		   
			}
			else {
				$new_height = $requested_height;
				$new_width = $new_height * $ratio;
				if ($requested_width == 0)
					$requested_width = $new_width;
			   
				if ($new_width < $requested_width) {
					$new_width = $requested_width;
					$new_height = $new_width / $ratio;
				}
			}
		   
			$new_width = ceil($new_width);
			$new_height = ceil($new_height);
		   
			//Resizing
			$config = array();
			$config['image_library'] = 'gd2';
			$config['source_image'] = $image_path;
			$config['new_image'] = $new_image_path;
			$config['maintain_ratio'] = true;
			$config['height'] = $new_height;
			$config['width'] = $new_width;
			$CI->image_lib->initialize($config);
			$CI->image_lib->resize();
			$CI->image_lib->clear();
		   
			//Crop if both width and height are not zero
			if (($width != 0) && ($height != 0)) {
				$x_axis = floor(($new_width - $width) / 2);
				$y_axis = floor(($new_height - $height) / 2);
			   
				//Cropping
				$config = array();
				$config['source_image'] = $new_image_path;
				$config['maintain_ratio'] = FALSE;
				$config['new_image'] = $new_image_path;
				$config['width'] = $width;
				$config['height'] = $height;
				$config['x_axis'] = $x_axis;
				$config['y_axis'] = $y_axis;
				$CI->image_lib->initialize($config);
				$CI->image_lib->crop();
				$CI->image_lib->clear();
			}
		}
		return $new_image_name;
  }
	
	public function createThumbWithPostfix($postfix, $image_path, $width = 0, $height = 0) {
		//Get the Codeigniter object by reference
		$CI = & get_instance();
		
		//The new generated filename we want
		$fileinfo = pathinfo($image_path);
		$new_image_path = $fileinfo['dirname'] . '/thumb/' . str_replace('Original_', '', preg_replace("/[^a-zA-Z0-9]+/", "", $fileinfo['filename'])) .$postfix. '.' . $fileinfo['extension'];
		$new_image_name = str_replace('Original_', '', preg_replace("/[^a-zA-Z0-9]+/", "", $fileinfo['filename'])) .$postfix. '.' . $fileinfo['extension'];
		
		//Harus image baru. tidak boleh ada nama file yg sama sebelumnya
		if ((! file_exists($new_image_path)) || filemtime($new_image_path) < filemtime($image_path)) {
			$CI->load->library('image_lib');
		   
			//The original sizes
			$original_size = getimagesize($image_path);
			$original_width = $original_size[0];
			$original_height = $original_size[1];
			$ratio = $original_width / $original_height;
		   
			//The requested sizes
			$requested_width = $width;
			$requested_height = $height;
		   
			//Initialising
			$new_width = 0;
			$new_height = 0;
		   
			//Calculations
			if ($requested_width > $requested_height) {
				$new_width = $requested_width;
				$new_height = $new_width / $ratio;
				if ($requested_height == 0)
					$requested_height = $new_height;
			   
				if ($new_height < $requested_height) {
					$new_height = $requested_height;
					$new_width = $new_height * $ratio;
				}
		   
			}
			else {
				$new_height = $requested_height;
				$new_width = $new_height * $ratio;
				if ($requested_width == 0)
					$requested_width = $new_width;
			   
				if ($new_width < $requested_width) {
					$new_width = $requested_width;
					$new_height = $new_width / $ratio;
				}
			}
		   
			$new_width = ceil($new_width);
			$new_height = ceil($new_height);
		   
			//Resizing
			$config = array();
			$config['image_library'] = 'gd2';
			$config['source_image'] = $image_path;
			$config['new_image'] = $new_image_path;
			$config['maintain_ratio'] = true;
			$config['height'] = $new_height;
			$config['width'] = $new_width;
			$CI->image_lib->initialize($config);
			$CI->image_lib->resize();
			$CI->image_lib->clear();
		   
			//Crop if both width and height are not zero
			if (($width != 0) && ($height != 0)) {
				$x_axis = floor(($new_width - $width) / 2);
				$y_axis = floor(($new_height - $height) / 2);
			   
				//Cropping
				$config = array();
				$config['source_image'] = $new_image_path;
				$config['maintain_ratio'] = FALSE;
				$config['new_image'] = $new_image_path;
				$config['width'] = $width;
				$config['height'] = $height;
				$config['x_axis'] = $x_axis;
				$config['y_axis'] = $y_axis;
				$CI->image_lib->initialize($config);
				$CI->image_lib->crop();
				$CI->image_lib->clear();
			}
		}
		return $new_image_name;
	}
	
	public function resizePhoto($image_path, $width = 0, $height = 0, $new_image = true) {
		//Get the Codeigniter object by reference
		$CI = & get_instance();
		   
		//The new generated filename we want
		$fileinfo = pathinfo($image_path);
		$new_image_path = $fileinfo['dirname'] . '/' . str_replace('Original', '', preg_replace("/[^a-zA-Z0-9]+/", "", $fileinfo['filename'])) . '.' . $fileinfo['extension'];
		$new_image_name = str_replace('Original', '', preg_replace("/[^a-zA-Z0-9]+/", "", $fileinfo['filename'])) . '.' . $fileinfo['extension'];
		
		//Harus image baru. tidak boleh ada nama file yg sama sebelumnya, kecuali new_image di set false
		if ((! file_exists($new_image_path)) || filemtime($new_image_path) < filemtime($image_path) || $new_image == false) {
			$CI->load->library('image_lib');
		   
			//The original sizes
			$original_size = getimagesize($image_path);
			$original_width = $original_size[0];
			$original_height = $original_size[1];
			$ratio = $original_width / $original_height;
		   
			//The requested sizes
			$requested_width = $width;
			$requested_height = $height;
		   
			//Initialising
			$new_width = 0;
			$new_height = 0;
		   
			//Calculations
			if ($requested_width > $requested_height) {
				$new_width = $requested_width;
				$new_height = $new_width / $ratio;
				if ($requested_height == 0)
					$requested_height = $new_height;
			   
				if ($new_height < $requested_height) {
					$new_height = $requested_height;
					$new_width = $new_height * $ratio;
				}
		   
			}
			else {
				$new_height = $requested_height;
				$new_width = $new_height * $ratio;
				if ($requested_width == 0)
					$requested_width = $new_width;
			   
				if ($new_width < $requested_width) {
					$new_width = $requested_width;
					$new_height = $new_width / $ratio;
				}
			}
		   
			$new_width = ceil($new_width);
			$new_height = ceil($new_height);
		   
			//Resizing
			$config = array();
			$config['image_library'] = 'gd2';
			$config['source_image'] = $image_path;
			if($new_image){
				$config['new_image'] = $new_image_path;
			}
			$config['maintain_ratio'] = true;
			$config['height'] = $new_height;
			$config['width'] = $new_width;
			$CI->image_lib->initialize($config);
			$CI->image_lib->resize();
			$CI->image_lib->clear();
		   
			//Crop if both width and height are not zero
			if (($width != 0) && ($height != 0)) {
				$x_axis = floor(($new_width - $width) / 2);
				$y_axis = floor(($new_height - $height) / 2);
			   
				//Cropping
				$config = array();
				$config['source_image'] = $new_image_path;
				$config['maintain_ratio'] = FALSE;
				if($new_image){
					$config['new_image'] = $new_image_path;
				}
				$config['width'] = $width;
				$config['height'] = $height;
				$config['x_axis'] = $x_axis;
				$config['y_axis'] = $y_axis;
				$CI->image_lib->initialize($config);
				$CI->image_lib->crop();
				$CI->image_lib->clear();
			}
		}
		return $new_image_name;
	}
	
	public function resizePhotoWithRatio($image_path, $width = 0, $height = 0, $new_image = true) {
		//Get the Codeigniter object by reference
		$CI = & get_instance();
		   
		//The new generated filename we want
		$fileinfo = pathinfo($image_path);
		$new_image_path = $fileinfo['dirname'] . '/' . str_replace('Original', '', preg_replace("/[^a-zA-Z0-9]+/", "", $fileinfo['filename'])) . '.' . $fileinfo['extension'];
		$new_image_name = str_replace('Original', '', preg_replace("/[^a-zA-Z0-9]+/", "", $fileinfo['filename'])) . '.' . $fileinfo['extension'];
		
		//Harus image baru. tidak boleh ada nama file yg sama sebelumnya, kecuali new_image di set false
		if ((! file_exists($new_image_path)) || filemtime($new_image_path) < filemtime($image_path) || $new_image == false) {
			$CI->load->library('image_lib');
		   
			//The original sizes
			$original_size = getimagesize($image_path);
			$original_width = $original_size[0];
			$original_height = $original_size[1];
			$ratio = $original_width / $original_height;
		   
			//The requested sizes
			$requested_width = $width;
			$requested_height = $height;
		   
			//Initialising
			$new_width = 0;
			$new_height = 0;
		   
			//Calculations
			if ($requested_width > $requested_height) { //landscape
				$new_width = $requested_width;
				$new_height = $new_width / $ratio;
				if ($requested_height == 0)
					$requested_height = $new_height;
			   
				if ($new_height < $requested_height) {
					$new_height = $requested_height;
					$new_width = $new_height * $ratio;
				}
		   
			}
			else { //potrait
				$new_height = $requested_height;
				$new_width = $new_height * $ratio;
				if ($requested_width == 0)
					$requested_width = $new_width;
			   
				if ($new_width < $requested_width) {
					$new_width = $requested_width;
					$new_height = $new_width / $ratio;
				}
			}
		   
			$new_width = ceil($new_width);
			$new_height = ceil($new_height);
		   
			//Resizing
			$config = array();
			$config['image_library'] = 'gd2';
			$config['source_image'] = $image_path;
      if($new_image){
				$config['new_image'] = $new_image_path;
			}
			$config['maintain_ratio'] = true;
			$config['height'] = $new_height;
			$config['width'] = $new_width;
			
			$CI->image_lib->initialize($config);
			$CI->image_lib->resize();
			$CI->image_lib->clear();
		}
		return $new_image_name;
	}
	
	public function resizePhotoWithRatioByHeight($image_path, $height = 0, $new_image = false) {
		//Get the Codeigniter object by reference
		$CI = & get_instance();
		   
		//The new generated filename we want
		$fileinfo = pathinfo($image_path);
		$new_image_path = $fileinfo['dirname'] . '/' . str_replace('Original_', '', preg_replace("/[^a-zA-Z0-9]+/", "", $fileinfo['filename'])) . '.' . $fileinfo['extension'];
		$new_image_name = str_replace('Original_', '', preg_replace("/[^a-zA-Z0-9]+/", "", $fileinfo['filename'])) . '.' . $fileinfo['extension'];
		
		//Harus image baru. tidak boleh ada nama file yg sama sebelumnya, kecuali new_image di set false
		if ((! file_exists($new_image_path)) || filemtime($new_image_path) < filemtime($image_path) || $new_image == false) {
			$CI->load->library('image_lib');
		   
			//The original sizes
			$original_size = getimagesize($image_path);
			$original_width = $original_size[0];
			$original_height = $original_size[1];
			$ratio = $original_width / $original_height;
		  
			//Initialising
			$new_height = 0;
			$new_height = ceil($height);
      
			//Resizing
			$config = array();
			$config['image_library'] = 'gd2';
			$config['source_image'] = $image_path;
			$config['maintain_ratio'] = true;
			$config['height'] = $new_height;
			$config['master_dim'] = 'height';
			if($new_image){
				$config['new_image'] = $new_image_path;
			}
			$CI->image_lib->initialize($config);
			$CI->image_lib->resize();
			$CI->image_lib->clear();
		}
		return $new_image_name;
	}
  
  public function resizePhotoWithRatioByWidth($image_path, $width = 0, $new_image = false) {
		//Get the Codeigniter object by reference
		$CI = & get_instance();
		   
		//The new generated filename we want
		$fileinfo = pathinfo($image_path);
		$new_image_path = $fileinfo['dirname'] . '/' . str_replace('Original_', '', preg_replace("/[^a-zA-Z0-9]+/", "", $fileinfo['filename'])) . '.' . $fileinfo['extension'];
		$new_image_name = str_replace('Original_', '', preg_replace("/[^a-zA-Z0-9]+/", "", $fileinfo['filename'])) . '.' . $fileinfo['extension'];
		
		//Harus image baru. tidak boleh ada nama file yg sama sebelumnya, kecuali new_image di set false
		if ((! file_exists($new_image_path)) || filemtime($new_image_path) < filemtime($image_path) || $new_image == false) {
			$CI->load->library('image_lib');
		   
			//The original sizes
			$original_size = getimagesize($image_path);
			$original_width = $original_size[0];
			$original_height = $original_size[1];
			$ratio = $original_width / $original_height;
		  
			//Initialising
			$new_width = 0;
			$new_width = ceil($width);
      
			//Resizing
			$config = array();
			$config['image_library'] = 'gd2';
			$config['source_image'] = $image_path;
			$config['maintain_ratio'] = true;
			$config['width'] = $new_width;
			$config['master_dim'] = 'width';
			if($new_image){
				$config['new_image'] = $new_image_path;
			}
			$CI->image_lib->initialize($config);
			$CI->image_lib->resize();
			$CI->image_lib->clear();
		}
		return $new_image_name;
	}
	
	public function cropImage($image_path, $x1, $y1, $width, $height, $new_image = false){
		//Get the Codeigniter object by reference
		$CI = & get_instance();
		   
		//The new generated filename we want
		$fileinfo = pathinfo($image_path);
		$new_image_path = $fileinfo['dirname'] . '/' . str_replace('Original_', '', preg_replace("/[^a-zA-Z0-9]+/", "", $fileinfo['filename'])) . '.' . $fileinfo['extension'];
		$new_image_name = str_replace('Original_', '', preg_replace("/[^a-zA-Z0-9]+/", "", $fileinfo['filename'])) . '.' . $fileinfo['extension'];
		
		//Harus image baru. tidak boleh ada nama file yg sama sebelumnya, kecuali new_image di set false
		if ((! file_exists($new_image_path)) || filemtime($new_image_path) < filemtime($image_path) || $new_image == false) {
			$CI->load->library('image_lib');
		   
			//The original sizes
			$original_size = getimagesize($image_path);
			$original_width = $original_size[0];
			$original_height = $original_size[1];
		   
			//The requested sizes
			$requested_width = $width;
			$requested_height = $height;
		   
			//Cropping
			$config = array();
			$config['image_library'] = 'gd2';
			$config['source_image'] = $image_path;
			$config['maintain_ratio'] = FALSE;
			$config['overwrite'] = TRUE;
			$config['width'] = $requested_width;
			$config['height'] = $requested_height;
			$config['x_axis'] = $x1;
			$config['y_axis'] = $y1;
			if($new_image){
				$config['new_image'] = $new_image_path;
			}
			$CI->image_lib->initialize($config);
			$CI->image_lib->crop();
      $CI->image_lib->clear();
		}

		return $new_image_name;
	}
	
	public function cropImageByScale($image_path, $scale_w, $x1, $y1, $x2, $y2, $new_image = false){
		//Get the Codeigniter object by reference
		$CI = & get_instance();
		   
		//The new generated filename we want
		$fileinfo = pathinfo($image_path);
		$new_image_path = $fileinfo['dirname'] . '/' . str_replace('Original_', '', preg_replace("/[^a-zA-Z0-9]+/", "", $fileinfo['filename'])) . '.' . $fileinfo['extension'];
		$new_image_name = str_replace('Original_', '', preg_replace("/[^a-zA-Z0-9]+/", "", $fileinfo['filename'])) . '.' . $fileinfo['extension'];
		
		//Harus image baru. tidak boleh ada nama file yg sama sebelumnya, kecuali new_image di set false
		if ((! file_exists($new_image_path)) || filemtime($new_image_path) < filemtime($image_path) || $new_image == false) {
			$CI->load->library('image_lib');
		   
			//The original sizes
			$original_size = getimagesize($image_path);
			$original_width = $original_size[0];
			$original_height = $original_size[1];
		   
			$requested_width = ($x2 - $x1);
			$requested_height = ($y2 - $y1);
			$x_axis = $x1;
			$y_axis = $y1;
			
		   
			if($original_width > $scale_w){
				//get image width ratio..
				$scaleRatio = $original_width / $scale_w;
			   
				//The requested sizes
				$requested_width = ($x2 - $x1) * $scaleRatio;
				$requested_height = ($y2 - $y1) * $scaleRatio;
				
				//cek for blank area..
				$x_axis = $x1 * $scaleRatio;
				$y_axis = $y1 * $scaleRatio;
			}
			
			if($original_width < ($x_axis + $requested_width)){
				$x_axis = $x_axis - (($x_axis + $requested_width) - $original_width);
			}
			if($original_height< ($y_axis + $requested_height)){
				$y_axis = $y_axis - (($y_axis + $requested_height) - $original_height);
			}
		   
			//Cropping
			$config = array();
			$config['image_library'] = 'gd2';
			$config['source_image'] = $image_path;
			$config['maintain_ratio'] = FALSE;
			$config['width'] = $requested_width;
			$config['height'] = $requested_height;
			$config['x_axis'] = $x_axis;
			$config['y_axis'] = $y_axis;
			if($new_image){
				$config['new_image'] = $new_image_path;
			}
			$CI->image_lib->initialize($config);
			$CI->image_lib->crop();
			$CI->image_lib->clear();
		}

		return $new_image_name;
	}
	
  public function rotatePhoto($image_path, $rotation_angle){
		//Get the Codeigniter object by reference
		$CI = & get_instance();
		$CI->load->library('image_lib');
		
		$config = array();
		$config['image_library'] = 'gd2';
		$config['source_image'] = $image_path;
		$config['rotation_angle'] = $rotation_angle;
		$CI->image_lib->initialize($config);
		$CI->image_lib->rotate();
		$CI->image_lib->clear();
	}

  public function generateWatermark($image_path, $filename){
    //Get the Codeigniter object by reference
    $CI = &get_instance();

    //The new generated filename we want
    $fileinfo = pathinfo($image_path);
    $new_image_path = $fileinfo['dirname'] . '/' . $filename;
    $new_image_name = $filename;
    $watermark = 'assets/example/watermark.png';

    if(strtolower($fileinfo['extension']) == 'png'){
      $image_src  = @imagecreatefrompng($new_image_path); //bg image
    }
    else{
      $image_src  = @imagecreatefromjpeg($new_image_path); //bg image
    }
    $watermark_src = @imagecreatefrompng($watermark); //overlay image

    $img_w = imagesx($image_src);
    $img_h = imagesy($image_src);
    $new = imagecreatetruecolor($img_w, $img_h);
    imagecopy($new, $image_src, 0, 0, 0, 0, $img_w, $img_h);
    imagedestroy($image_src);

    $watermark_width = imagesx($watermark_src);
    $watermark_height = imagesy($watermark_src);
    $dest_x = ($img_w - $watermark_width) / 2;
    $dest_y = ($img_h - $watermark_height) / 2;
    imagecopy($new,$watermark_src, $dest_x, $dest_y, 0, 0,$watermark_width,$watermark_height);
    imagejpeg($new, $new_image_path); //image_src, image_destination
    imagedestroy($image_src);
    imagedestroy($watermark_src);
    return $new_image_name;
  }
  
}