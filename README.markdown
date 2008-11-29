Image Resize for Frog
=====================

This plugin has been tested with Frog version 0.9.4. It's homepage is:

`http://www.naehrstoff.ch/code/image-resize-for-frog`


About
-----

With the Image Resize plugin enabled, you can easily create thumbnails of your images.

It was inspired by the Drupal Image Cache module. The image resize functions are taken from Drupal and have been adapted for Frog CMS.


How to use
----------

Just include images as you normally would, but instead of linking to the original file, append a dimension to the filename. This is best shown with an example.

Where you would normally reference an image like this:

`<img src="public/images/flower.jpg" alt="" />`

You can now add a dimension identifier directly before the file extension like this:

`<img src="public/images/flower.230x150.jpg" alt="" />`

This tells the plugin to generate an image that fits within the width of 230 and the height of 150 while keeping the aspect ratio intact.

If you want to crop the image to the specified dimensions, you can add the option `c` to the end of the dimension identifier like this:

`<img src="public/images/flower.230x150c.jpg" alt="" />`

This will output an image that is exactly 230x150 pixels in size, but it also means that part of the original image will not be shown.

It is possible to use only one parameter as an argument:

* `<img src="public/images/flower.230.jpg" alt="" />` (resize to width)
* `<img src="public/images/flower.x150.jpg" alt="" />` (resize to height)
* `<img src="public/images/flower.100c.jpg" alt="" />` (crop to square)

Since version 1.1.0 you can also use a PHP-function to create the image tags:

`<?php echo image_resize_image_tag($path_to_file, $width = NULL, $height = NULL, $options = array()); ?>`

Of course the original file (flower.jpg) has to exist. The thumbnails will be created in the same folder as the original file.


Contributors
------------

* Peter Gassner <peter@naehrstoff.ch>
* Mika Tuupola <tuupola@appelsiini.net>


Changelog
---------

* 1.1.1 (November 21, 2008)
	* Changed name of Github repository to prevent errors when installing the plugin.
* 1.1.0 (November 12, 2008)
	* Added globally accessible function `image_resize_image_tag()` to create image tags programmatically.
	* Removed need to reload after thumbnail creation
	* Updated for compatibility with Frog v0.9.4
* 1.0.0 (February 3, 2008)
	* First version for Frog v0.9.2