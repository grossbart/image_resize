<?php

include("ImageResize.php");

Plugin::setInfos(array(
	'id'          => 'image_resize',
	'title'       => 'Image Resize',
	'description' => 'Allows for dynamic resizing of images.',
	'version'     => '1.1.0',
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
    $path = $_SERVER['QUERY_STRING'];
    if (preg_match('#^public\/.+\.(jpg|jpeg|gif|png)#i', $path)) {
        // If the requested file is within the /public folder
        // and is an accepted format, resize and redirect to the
        // newly created image.
        image_resize_scale($path);
        header('Location: '. URL_PUBLIC . "/" . $path);
        // Exit here to prevent a page not found message
        exit();
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
    if (preg_match('#(.+)\.([0-9]+)x?\.([a-z]+)$#i', $namepart, $match)) {
        // imagename.230.jpg or imagename.230x.jpg
        $filename = $match[1].".".$match[3];
        $width  = (int)$match[2];
        $height = NULL;
    } else if (preg_match('#(.+)\.x([0-9]+)\.([a-z]+)$#i', $namepart, $match)) {
        // imagename.x150.jpg
        $filename = $match[1].".".$match[3];
        $width  = NULL;
        $height = (int)$match[2];
    } else if (preg_match('#(.+)\.([0-9]+)x([0-9]+)\.([a-z]+)$#i', $namepart, $match)) {
        // imagename.230x150.jpg
        $filename = $match[1].".".$match[4];
        $width  = (int)$match[2];
        $height = (int)$match[3];
    } else {
        // no resizing, fail silently
        return FALSE;
    }

    if (ImageResize::gd_available()) {
        ImageResize::image_scale($server_path."/".$filename, $server_path."/".$namepart, $width, $height);
    }
}


/**
 * Helper function to create an image tag
 */
function image_resize_image_tag($image_path, $width = NULL, $height = NULL, $options = array()) {
    if (!array_key_exists("alt", $options)) $options['alt'] = '';
    $html_options = "";
    foreach ($options as $key => $value) {
      $html_options .= "$key='$value' ";
    }
    $image_path = preg_replace('#(.+)\.(jpg|jpeg|gif|png)$#i', '${1}.'.$width.'x'.$height.'.${2}', $image_path);
    return "<img src='$image_path' $html_options/>";
}


