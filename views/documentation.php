<h1>Documentation</h1>
<h2>Image Resize</h2>

<p>With the <em>Image Resize</em> plugin enabled, you can easily create thumbnails of your images.</p>

<h3>Status</h3>

<?php if ($gd_status && $mod_rewrite_status): ?>
    <p>All requirements seem to be met, this module should work as expected. If you have problems with blank thumbnails, please check whether all image formats are supported by your system.</p>
<?php else: ?>
    <p>Some problems were encountered, this module may not work correctly.</p>
<?php endif; ?>

<ul>
    <?php if ($gd_status): ?>
        <li style="color: #3a3">The GD library is available.
        <ul>
          <?php foreach(gd_info() as $key => $value): ?>
          <?php if ($value == '') $value = "0" ?>
          <li style="padding-left: 24px; color: #666; font-size: 0.8em"><?php echo "$key: $value" ?></li>
          <?php endforeach; ?>
         </ul>
        </li>
    <?php else: ?>
        <li style="color: #a33">The GD library is not available on this system. Refer to the <a href="http://www.php.net/gd">PHP GD documentation</a> for help.</li>        
    <?php endif; ?>
    <?php if ($mod_rewrite_status): ?>
        <li style="color: #3a3">mod_rewrite is active.</li>
    <?php else: ?>
        <li style="color: #a33">mod_rewrite is not active. Check the settings in your config.php and .htaccess files.</li>        
    <?php endif; ?>
</ul>

<h3>How to use</h3>

<p>Just include images as you normally would, but instead of linking to the original file, append a dimension to the filename. This is best shown with an example.</p>

<p>Where you would normally reference an image like this:</p>

<p><code>&lt;img src="public/images/flower.jpg" alt="" /&gt;</code></p>

<p>You can now add a dimension identifier directly before the file extension like this:</p>

<p><code>&lt;img src="public/images/flower.<strong>230x150</strong>.jpg" alt="" /&gt;</code></p>

<p>This tells the plugin to generate an image that fits within the width of 230 and the height of 150 while keeping the aspect ratio intact.</p>

<p>If you want to crop the image to the specified dimensions, you can add the option <code>c</code> to the end of the dimension identifier like this:</p>

<p><code>&lt;img src="public/images/flower.230x150c.jpg" alt="" /&gt;</code></p>

<p>This will output an image that is exactly 230x150 pixels in size, but it also means that part of the original image will not be shown.</p>

<p>It is possible to use only one parameter as an argument:</p>

<ul>
    <li><code>&lt;img src="public/images/flower.<strong>230</strong>.jpg" alt="" /&gt;</code> (resize to width)</li>
    <li><code>&lt;img src="public/images/flower.<strong>x150</strong>.jpg" alt="" /&gt;</code> (resize to height)</li>
    <li><code>&lt;img src="public/images/flower.100c.jpg" alt="" /&gt;</code> (crop to square)</li>
</ul>

<p>Since version 1.1 you can also use a PHP-function to create the image tags. This function has changed in 1.2 to allow for more options like cropping. Calling it with version 1.1 arguments still works but is deprecated. Use these options from now on:</p>

<p><code>&lt;?php echo image_resize_image_tag($path_to_file, array("width"=&gt;100, "height"=&gt;50, "crop"=&gt;TRUE), array("alt"=&gt;"Beautiful flower", "class"=&gt;"photo")) ?&gt;</code></p>

<p>Of course the original file (<code>flower.jpg</code>) has to exist. The thumbnails will be created in the same folder as the original file.</p>


<h3>Trouble shooting</h3>

<h4>Must be logged in</h4>

<p>Please note, that as of version 1.3 administrator, developer or editor rights are needed to scale an image for security reasons. So make sure you're logged in.</p>

<h4>How to use Image Resize together with the Page Not Found plugin</h4>

<p>If you want to use Image Resize with Frog&#8217;s Page Not Found plugin, you will have to include the following code at the top of your customized Page Not Found page:</p>

<p><code>&lt;?php image_resize_try_resizing() ?&gt;</code></p>

<p>This is necessary because the Page Not Found plugin will be called before Image Resize, so the call will never make it to Image Resize which in turn can&#8217;t convert the image.</p>

<p>For some reason you will have to reload a page twice before the image appears.</p>


<h4>How to activate mod_rewrite</h4>

<p>To get mod_rewrite to work, you&#8217;ll have to make sure that it is:</p>

<ul>
<li>Possible to use mod_rewrite on your server</li>
<li>You rename the file <code>_.htaccess</code> to <code>.htaccess</code> (without the leading underscore)</li>
<li>You set the variable <code>USE_MOD_REWRITE</code> in Frog&#8217;s config.php to true.</li>
</ul>


<h3>How does it work?</h3>

<p>This module relies on some <code>mod_rewrite</code> magic: If the requested thumbnail exists, Apache knows this and delivers the file to the browser. If the file doesn't exist, Apache hands control over to Frog CMS.</p>

<p>Frog won't find the file either, so it sends a <code>page_not_found</code> message. This is the moment where the Image Resize plugin suddenly feels responsible. It checks whether an image without the dimension specifier exists (<code>flower.jpg</code>) and converts it into a thumbnail with the requested name (<code>flower.230x150.jpg</code>). The next time someone asks for that file it will be there!</p>

<h3>About this plugin</h3>

<p>This plugin was created by <a href="http://www.naehrstoff.ch">Peter Gassner</a> for the deliciously small and elegant <a href="http://madebyfrog.com">Frog CMS</a>.</p>

<p>It was inspired by the Drupal <a href="http://drupal.org/project/imagecache">Image Cache</a> module. The image resize functions are taken from <a href="http://drupal.org/">Drupal</a> and adapted for Frog CMS.</p>

<p>The plugin can be installed via Github, where you can either download a ZIP-file or checkout the Git repository.</p>

<p><a href="http://github.com/naehrstoff/image_resize/tree/master">Download the plugin</a> from Github.</p>

<p>Patches and suggestions are very welcome!</p>