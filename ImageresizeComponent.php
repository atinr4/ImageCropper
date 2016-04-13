<?php

App::uses('Component', 'Controller');

class ImageresizeComponent extends Component {

	/**
	 * @desc: this function is use to create Image Thumbnail and Cropper
	 * @param: $src_path is path of directory of source image,
	 *         $thumb_path is path where new image will be save,
	 *         $thumb_width is the width of thumb image and
	 *         $image_file_name will the name of image file
	 * @author Atin Roy
	 * @created on 12-03-2016
	 */

	function resizeImage($filename, $max_width, $max_height, $crop_height) {
		list($orig_width, $orig_height) = getimagesize($filename);

		$width = $orig_width;
		$height = $orig_height;

		# taller
		if ($height > $max_height) {
			$width = ($max_height / $height) * $width;
			$height = $max_height;
		}

		# wider
		if ($width > $max_width) {
			$height = ($max_width / $width) * $height;
			$width = $max_width;
		}

		$image_p = imagecreatetruecolor($width, $height);

		switch (exif_imagetype($filename)) {
		case 1:
			$image = imagecreatefromgif($filename);
			imagecopyresampled($image_p, $image, 0, 0, 0, 0,
				$width, $height, $orig_width, $orig_height);

			$file_information = pathinfo($filename);

			$new_image = $file_information['dirname'] . DS . $file_information['filename'] . '_' . $width . 'x' . $height . '.' . $file_information['extension'];
			imagegif($image_p, $file_information['dirname'] . DS . $file_information['filename'] . '_' . $width . 'x' . $height . '.' . $file_information['extension']);
			break;

		case 2:
			$image = imagecreatefromjpeg($filename);
			imagecopyresampled($image_p, $image, 0, 0, 0, 0,
				$width, $height, $orig_width, $orig_height);

			$file_information = pathinfo($filename);

			$new_image = $file_information['dirname'] . DS . $file_information['filename'] . '_' . $width . 'x' . $height . '.' . $file_information['extension'];
			imagejpeg($image_p, $file_information['dirname'] . DS . $file_information['filename'] . '_' . $width . 'x' . $height . '.' . $file_information['extension'], 100);
			break;

		case 3:
			$image = imagecreatefrompng($filename);

			imagecopyresampled($image_p, $image, 0, 0, 0, 0,
				$width, $height, $orig_width, $orig_height);

			$file_information = pathinfo($filename);

			$new_image = $file_information['dirname'] . DS . $file_information['filename'] . '_' . $width . 'x' . $height . '.' . $file_information['extension'];
			imagepng($image_p, $file_information['dirname'] . DS . $file_information['filename'] . '_' . $width . 'x' . $height . '.' . $file_information['extension'], 9);
			break;

		}
		$this->image_crop($new_image, $max_width, $crop_height);

	}
	function image_crop($image_file, $width, $height) {

		if (file_exists($image_file)) {
			$file_information = pathinfo($image_file);

			$file_information['filename'] = explode('_', $file_information['filename'])[0];

			switch (exif_imagetype($image_file)) {
			case 1:
				$im = imagecreatefromgif($image_file);

				$ini_x_size = getimagesize($image_file)[0];
				$ini_y_size = getimagesize($image_file)[1];

				//the minimum of xlength and ylength to crop.
				$crop_measure = min($ini_x_size, $ini_y_size);

				// Set the content type header - in this case image/jpeg
				//header('Content-Type: image/jpeg');

				$to_crop_array = array('x' => 0, 'y' => 0, 'width' => $width, 'height' => $height);
				$thumb_im = imagecrop($im, $to_crop_array);

				imagegif($thumb_im, $file_information['dirname'] . DS . $file_information['filename'] . '_' . $width . 'x' . $height . '.' . $file_information['extension']);
				break;
			case 2:
				$im = imagecreatefromjpeg($image_file);

				$ini_x_size = getimagesize($image_file)[0];
				$ini_y_size = getimagesize($image_file)[1];

				//the minimum of xlength and ylength to crop.
				$crop_measure = min($ini_x_size, $ini_y_size);

				// Set the content type header - in this case image/jpeg
				//header('Content-Type: image/jpeg');

				$to_crop_array = array('x' => 0, 'y' => 0, 'width' => $width, 'height' => $height);
				$thumb_im = imagecrop($im, $to_crop_array);

				imagejpeg($thumb_im, $file_information['dirname'] . DS . $file_information['filename'] . '_' . $width . 'x' . $height . '.' . $file_information['extension'], 100);
				break;
			case 3:
				$im = imagecreatefrompng($image_file);

				$ini_x_size = getimagesize($image_file)[0];
				$ini_y_size = getimagesize($image_file)[1];

				//the minimum of xlength and ylength to crop.
				$crop_measure = min($ini_x_size, $ini_y_size);

				// Set the content type header - in this case image/jpeg
				//header('Content-Type: image/jpeg');

				$to_crop_array = array('x' => 0, 'y' => 0, 'width' => $width, 'height' => $height);
				$thumb_im = imagecrop($im, $to_crop_array);

				imagepng($thumb_im, $file_information['dirname'] . DS . $file_information['filename'] . '_' . $width . 'x' . $height . '.' . $file_information['extension'], 9);
				break;

			}

		}
	}
}