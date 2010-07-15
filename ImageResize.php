<?php

/**
 *  Helper functions, many from Drupal
 *  http://drupal.org
 */

class ImageResize {
    protected static $infoCache = array();
  
    public static function image_scale($source, $destination, $width, $height) {
        if (!$info = self::image_get_info($source)) {
            // file isn't an image
            return false;
        }
        // don't scale up
        if ($width > $info['width'] && $height > $info['height']) {
            return false;
        }

        $aspect = $info['height'] / $info['width'];
        if (!$height || ($width && $aspect < $height / $width)) {
            $width = (int)min($width, $info['width']);
            $height = (int)round($width * $aspect);
        } else {
            $height = (int)min($height, $info['height']);
            $width = (int)round($height / $aspect);
        }
        return self::image_gd_resize($source, $destination, $width, $height);
    }
  
    public static function image_scale_cropped($source, $destination, $width, $height) {
        if (!$info = self::image_get_info($source)) {
            // file isn't an image
            return false;
        }

        // don't scale up
        if ($width > $info['width'] && $height > $info['height']) {
            return false;
        }
            
        /* If we are square. */
        if (!$height || !$width || $height == $width) {
            if ($info['width'] > $info['height']) {
                $source_width = $source_height = $info['height'];
                $source_y = 0;
                $source_x = round(($info['width'] - $info['height']) / 2);
            } else {
                $source_width = $source_height = $info['width'];
                $source_x = 0;
                $source_y = round(($info['height'] - $info['width']) / 2);
            }
            if ($width) {
                $height = $width;
            } else {
                $width = $height;
            }
      /* We are not square. */
        } else {
            $x_ratio = $width / $info['width'];
            $y_ratio = $height / $info['height'];

            if (($x_ratio * $info['width']) >= $width  && ($x_ratio * $info['height']) >= $height) {
                $aspect  = $width / $height;
                $x_ratio * $info['width'];
                $source_width  = $info['width'];
                $source_height = round($source_width / $aspect);
                $source_x = 0;
                $source_y = round(($info['height'] - $source_height) / 2);
            } else {
                $aspect  = $height / $width;
                $source_height = $info['height'];
                $source_width  = round($source_height / $aspect);
                $source_x = round(($info['width'] - $source_width) / 2);
                $source_y = 0;

            }
        }
        return self::image_gd_resize($source, $destination, $width, $height,  $source_x, $source_y, $source_width, $source_height);
    }
  
  
    /**
     * GD2 has to be available on the system
     *
     * @return boolean
     */
    public static function gd_available() {
        if (extension_loaded('gd') && 
         function_exists('imagecreatetruecolor') && 
         function_exists('imagecopyresampled') &&
         function_exists('imagedestroy') &&
         function_exists('getimagesize')) {
            return true;
        }
        return false;
    }
  
  
    /**
     * Get details about an image.
     *
     * @return array containing information about the image
     *      'width': image's width in pixels
     *      'height': image's height in pixels
     *      'extension': commonly used extension for the image
     *      'mime_type': image's MIME type ('image/jpeg', 'image/gif', etc.)
     */
    public static function image_get_info($file) {
        if (!file_exists($file)) {
            return false;
        }
        // getimagesize() can apparently be expensive, so we'll cache results,
        // checking against filemtime() to make sure the file is still the same
        clearstatcache();
        if (isset(self::$infoCache[$file]) && self::$infoCache[$file]['lastmod']===@filemtime($file)) {
            return self::$infoCache[$file];
        }
        
        // Gather metadata about the requested image file
        $data = @getimagesize($file);
        // If file isn't an image, stop
        if (!$data) {
            self::$infoCache[$file] = false;
            return self::$infoCache[$file];
        } else {
            $extensions = array(1 => 'gif', 2 => 'jpeg', 3 => 'png', 15 => 'wbmp');
            $extension = array_key_exists($data[2], $extensions) ?  $extensions[$data[2]] : '';
            $format = ($extension=="jpg") ? "jpeg" : $extension;
            $details = array('width'     => $data[0],
                             'height'    => $data[1],
                             'image_type' => $data[2],
                             'extension' => $extension,
                             'format'    => $format,
                             'mime_type' => $data['mime'],
                             'lastmod'   => @filemtime($file));
            self::$infoCache[$file] = $details;
            return self::$infoCache[$file];
        }
    }

    /*
     *  Transparency Fix for gif and png by Maxim Chernyak
     *  http://mediumexposure.com/smart-image-resizing-while-preserving-transparency-php-and-gd-library/
     */
    public static function add_transparency($image_resized, $image, $info)
    {
        if ( ($info['image_type'] == IMAGETYPE_GIF) || ($info['image_type']  == IMAGETYPE_PNG) ) {
          $trnprt_indx = imagecolortransparent($image);

          // If we have a specific transparent color
          if ($trnprt_indx >= 0) {

            // Get the original image's transparent color's RGB values
            $trnprt_color    = imagecolorsforindex($image, $trnprt_indx);

            // Allocate the same color in the new image resource
            $trnprt_indx    = imagecolorallocate($image_resized, $trnprt_color['red'], $trnprt_color['green'], $trnprt_color['blue']);

            // Completely fill the background of the new image with allocated color.
            imagefill($image_resized, 0, 0, $trnprt_indx);

            // Set the background color for new image to transparent
            imagecolortransparent($image_resized, $trnprt_indx);


          }
          // Always make a transparent background color for PNGs that don't have one allocated already
          elseif ($info['image_type'] == IMAGETYPE_PNG) {

            // Turn off transparency blending (temporarily)
            imagealphablending($image_resized, false);

            // Create a new transparent color for image
            $color = imagecolorallocatealpha($image_resized, 0, 0, 0, 127);

            // Completely fill the background of the new image with allocated color.
            imagefill($image_resized, 0, 0, $color);

            // Restore transparency blending
            imagesavealpha($image_resized, true);
          }
        }

        return array($image_resized, $image);
    }


    /**
     * Scale an image to the specified size using GD.
     */
    protected static function image_gd_resize($source, $destination, $width, $height, $source_x = 0, $source_y = 0, $source_width = null, $source_height = null) {
        if (!file_exists($source)) {
            return false;
        }
        if (!$info = self::image_get_info($source)) {
            return false;
        }
        if (!$im = self::image_gd_open($source, $info['format'])) {
            return false;
        }
        /* Get source dimensions from GD info is not passed as parameters. */
        $source_width  = is_null($source_width)  ? $info['width']  : $source_width;
        $source_height = is_null($source_height) ? $info['height'] : $source_height;
    
        $res = imagecreatetruecolor($width, $height);

        /*
         *  GIF, PNG transparency fix
         */
        list($res, $im) = self::add_transparency($res, $im, $info);

        imagecopyresampled($res, $im, 0, 0, $source_x, $source_y, $width, $height,  $source_width, $source_height);
        if (!imageistruecolor($im)) {
            imagetruecolortopalette($res, false, 256);
        }
        $result = self::image_gd_write($res, $destination, $info['format']);

        imagedestroy($res);
        imagedestroy($im);

        return $result;
    }
  
  
    /**
     * GD helper function to create an image resource from a file.
     */
    protected static function image_gd_open($file, $format) {
        $open_func = 'imagecreatefrom'. $format;
        if (!function_exists($open_func)) {
            return false;
        }
        return $open_func($file);
    }
  

    /**
     * GD helper to write an image resource to a destination file.
     */
    protected static function image_gd_write($res, $destination, $format) {
        $write_func = 'image'. $format;
        if (!function_exists($write_func)) {
            return false;
        }
        if (DEBUG) {
            // If Frog is in debug mode, don't output to a file
            $destination = NULL;
            $types = array('jpeg'=>'jpeg','gif'=>'gif','png'=>'png','wbmp'=>'vnd.wap.wbmp');
            header('Content-Type: image/'.$types[$format]);
        }
        $args = array($res, $destination);
        switch($format) {
            // Set quality values for JPEG and PNG
            case 'jpeg': $args[] = 60; break;
            case 'png':  $args[] = 9;  break;
        }
        return call_user_func_array($write_func, $args);
    }
}
