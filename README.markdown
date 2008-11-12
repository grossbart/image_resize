IMAGE RESIZE

Peter Gassner, 2008
http://www.naehrstoff.ch/code/image-resize-for-frog

This plugin has been tested with Frog 0.9.2.


ABOUT

With the Image Resize plugin enabled, you can easily create thumbnails of your images.

It was inspired by the Drupal Image Cache module. The image resize functions are taken from Drupal and adapted for Frog CMS.


HOW TO USE

Just include images as you normally would, but instead of linking to the original file, append a dimension to the filename. This is best shown with an example.

Where you would normally reference an image like this:

* <img src="public/images/flower.jpg" alt="" />

You can now add a dimension identifier directly before the file extension like this:

* <img src="public/images/flower.230x150.jpg" alt="" />

This tells the plugin to generate an image that fits within the width of 230 and the height of 150. It is possible to use only one parameter as an argument:

* <img src="public/images/flower.230.jpg" alt="" /> (resize to width)
* <img src="public/images/flower.x150.jpg" alt="" /> (resize to height)

Of course the original file (flower.jpg) has to exist. The thumbnails will be created in the same folder as the original file.




