<?php

require_once("ImageResize.php");

Plugin::setInfos(array(
    'id'          => 'image_resize',
    'title'       => 'Image Resize',
    'description' => 'Allows for dynamic resizing of images.',
    'version'     => '1.2.1',
    'license'     => 'GPL 3.0',
    'author'      => 'Peter Gassner',
    'website'     => 'http://www.naehrstoff.ch/code/image-resize-for-frog',
    'update_url'  => 'http://frog.naehrstoff.ch/plugin-versions.xml',
    'require_frog_version' => '0.9.4'
));

// Listen for page_not_found messages
Observer::observe('page_not_found', 'image_resize_try_resizing');

// Add the controller without showing a tab (fourth option)
Plugin::addController('image_resize', 'Image Resize', '', FALSE);


/**
 * Execute this function on page_not_found.
 * If the request is for an image file,
 * resize the image.
 */
function image_resize_try_resizing() {
    // Check that gd library is available
    if (!ImageResize::gd_available()) {
        return false;
    }    
    if (preg_match('#\.(jpe?g|gif|png|wbmp)$#i', CURRENT_URI)) {
        // If requested file appears to be an accepted format, create the new image 
        if (image_resize_scale(CURRENT_URI) && !DEBUG) {
            // If Frog isn't debugging, it writes to a file; redirect to it
            header('Location: '. URL_PUBLIC . "/" . CURRENT_URI);
            // Exit here to prevent a page not found message
            exit();            
        }
    }
}


/**
 * Parse the filename of the requested image
 * for size information and resize accordingly.
 */
function image_resize_scale($path) {
    $params      = explode("/", $path);
    $namepart    = array_pop($params);
    $public_path = URL_PUBLIC . "/" . join("/", $params);
    $server_path = FROG_ROOT  . "/" . join("/", $params);
    
    // Dissect filename to find dimension information
    $pattern = <<<FILENAME_PATTERN
/^

# Acceptable input examples (non-exhaustive):
#  Width scaling:     file.200.jpg file.200x.jpg
#  Height scaling:    file.x200.jpg
#  Arbitrary scaling: file.200x200.jpg
#  Cropping:          file.200c.jpg file.200x200c.jpg

 (.+)         # source file name-part
 \.(?!x?c?\.) # prevents "image..jpg" and so forth
 (\d+)?       # width
 x?(\d+)?     # height
 (c)?         # optional crop
 (\.[a-z]+)   # source file extension

\$/ix
FILENAME_PATTERN;
    if (preg_match($pattern, $namepart, $match)) {
        $filename = $match[1].$match[5];
        $width    = (int) $match[2];
        $height   = (int) $match[3];
        $crop     = 'c' == $match[4];
        $source      = $server_path."/".$filename;
        $destination = $server_path."/".$namepart;
    } else {
        return false;
    }

    if ($crop) {
        return ImageResize::image_scale_cropped($source, $destination, $width, $height);
    } else {
        return ImageResize::image_scale($source, $destination, $width, $height);
    }

}


/**
 * Helper function to create an image tag
 *
 * @param string image path
 * @param array image options: "width", "height" or "crop"
 * @param array html attributes that will be added to the link tag
 */

function image_resize_image_tag($image_path, $options = array(), $html_attributes = array()) {

    // Maintain backwards compatibility
    $args = func_get_args();
    if (is_null($args[1])) {
        $options = array();
    }
    if (is_numeric($args[1])) {
        $options = array();
        $options['width'] = $args[1];
    }
    if (is_numeric($args[2])) {
        $options['height'] = $args[2];
    }
    if (is_array($args[3])) {
      $html_attributes = $args[3];
    }

    // Assign values
    $width  = (array_key_exists('width',  $options)) ? $options['width'] : NULL;
    $height = (array_key_exists('height', $options)) ? $options['height'] : NULL;
    $crop   = (array_key_exists('crop',   $options)) ? 'c' : "";

    if (!array_key_exists("alt", $html_attributes)) $html_attributes['alt'] = '';

    $attributes_string = " ";
    foreach ($html_attributes as $key => $value) {
        $attributes_string .= "$key='$value' ";
    }
    $image_path = preg_replace('#(.+)\.(jpg|jpeg|gif|png)$#i', '${1}.'.$width.'x'.$height.$crop.'.${2}', $image_path);
    return "<img src='$image_path'$attributes_string/>";
}

