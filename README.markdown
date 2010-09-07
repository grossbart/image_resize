Image Resize for Frog
=====================

This plugin has been tested with Frog version 0.9.4 and 0.9.5. It's homepage is:

`http://github.com/naehrstoff/image_resize/tree/master`


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

Since version 1.1 you can also use a PHP-function to create the image tags. This function has changed in 1.2 to allow for more options like cropping. Calling it with version 1.1 arguments still works but is deprecated. Use these options from now on:

`<?php echo image_resize_image_tag($path_to_file, array("width"=>100, "height"=>50, "crop"=>TRUE), array("alt"=>"Beautiful flower", "class"=>"photo")) ?>`

Of course the original file (flower.jpg) has to exist. The thumbnails will be created in the same folder as the original file.


Trouble shooting
-----------------

### Must be logged in

Please note, that as of version 1.3 administrator, developer or editor rights are needed to scale an image for security reasons. So make sure you're logged in.

### How to use Image Resize together with the Page Not Found plugin

If you want to use Image Resize with Frog's Page Not Found plugin, you will have to include the following code at the top of your customized Page Not Found page:

`<?php image_resize_try_resizing() ?>`

This is necessary because the Page Not Found plugin will be called before Image Resize, so the call will never make it to Image Resize which in turn can't convert the image.

For some reason you will have to reload a page twice before the image appears.

### How to activate mod_rewrite

To get mod_rewrite to work, you'll have to make sure that it is:

* Possible to use mod_rewrite on your server
* You rename the file `_.htaccess` to `.htaccess` (without the leading underscore)
* You set the variable `USE_MOD_REWRITE` in Frog's config.php to true.


Contributors
------------

* Peter Gassner <peter@naehrstoff.ch>
* Mika Tuupola <tuupola@appelsiini.net>
* J. King <jking@jkingweb.ca>
* René Kersten <info@pixel-webarts.de>

Changelog
---------

* 1.3.1 (July 15, 2010)
  * Fixed transparency for Gif and PNG images
* 1.3.0 (June 12, 2009)
	* Require editor, developer or administrator permissions before resizing for security reasons.
	* 8-bit PNGs are no longer converted to 24-bit.
	* Now outputs directly to the browser instead of file when Frog is in debug mode.
	* Improved sanity checks.
* 1.2.1 (February 28, 2009)
	* Fix compatibility between 0.9.4, 0.9.5 and SVN.
	* Prevent endless loop when trying to resize nonexistent image.
* 1.2.0 (November 29, 2008)
	* Added a cropping option to crop images to any dimension.
	* Removed restriction where images needed to be in public/ directory.
	* Changed image_resize_image_tag function to allow more options while keeping backwards compatibility.
* 1.1.1 (November 21, 2008)
	* Changed name of Github repository to prevent errors when installing the plugin.
* 1.1.0 (November 12, 2008)
	* Added globally accessible function `image_resize_image_tag()` to create image tags programmatically.
	* Removed need to reload after thumbnail creation
	* Updated for compatibility with Frog v0.9.4
* 1.0.0 (February 3, 2008)
	* First version for Frog v0.9.2