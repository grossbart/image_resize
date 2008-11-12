<?php

include("ImageResize.php");

class ImageResizeController extends PluginController {

    public function __construct() {
        $this->setLayout('backend');
    }
    
    public function documentation() {
        $this->display('image_resize/views/documentation', array(
          'gd_status'          => ImageResize::gd_available(),
          'mod_rewrite_status' => USE_MOD_REWRITE
        ));
    }
}






