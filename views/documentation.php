<h1>Documentation</h1>
<h2>Image Resize</h2>

<p>With the <em>Image Resize</em> plugin enabled, you can easily create thumbnails of your images.</p>

<h3>Status</h3>

<?php if ($gd_status && $mod_rewrite_status): ?>
    <p>All requirements are met, this module should work as expected.</p>
<?php else: ?>
    <p>Some problems were encountered, this module may not work correctly.</p>
<?php endif; ?>

<ul>
    <?php if ($gd_status): ?>
        <li style="color: #3a3">The GD library is available.</li>
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

<p>This tells the plugin to generate an image that fits within the width of 230 and the height of 150. It is possible to use only one parameter as an argument:</p>

<ul>
    <li><code>&lt;img src="public/images/flower.<strong>230</strong>.jpg" alt="" /&gt;</code> (resize to width)</li>
    <li><code>&lt;img src="public/images/flower.<strong>x150</strong>.jpg" alt="" /&gt;</code> (resize to height)</li>
</ul>

<p>Since version 1.1.0 you can also use a PHP-function to create the image tags:</p>

<p><code>&lt;?php echo image_resize_image_tag($path_to_file, $width = NULL, $height = NULL, $options = array()); ?&gt;</code></p>

<p>Of course the original file (<code>flower.jpg</code>) has to exist. The thumbnails will be created in the same folder as the original file.</p>


<h3>How does it work?</h3>

<p>This module relies on some <code>mod_rewrite</code> magic: If the requested thumbnail exists, Apache knows this and delivers the file to the browser. If the file doesn't exist, Apache hands control over to Frog CMS.</p>

<p>Frog won't find the file either, so it sends a <code>page_not_found</code> message. This is the moment where the Image Resize plugin suddenly feels responsible. It checks whether an image without the dimension specifier exists (<code>flower.jpg</code>) and converts it into a thumbnail with the requested name (<code>flower.230x150.jpg</code>). The next time someone asks for that file it will be there!</p>

<h3>About this plugin</h3>

<p>This plugin was created by <a href="http://www.naehrstoff.ch">Peter Gassner</a> for the deliciously small and elegant <a href="http://madebyfrog.com">Frog CMS</a>.</p>

<p>It was inspired by the Drupal <a href="http://drupal.org/project/imagecache">Image Cache</a> module. The image resize functions are taken from <a href="http://drupal.org/">Drupal</a> and adapted for Frog CMS.</p>

<p>The plugin can be installed via Github, where you can either download a ZIP-file or checkout the Git repository.</p>

<p><a href="http://github.com/naehrstoff/image_resize/tree/master">Download the plugin</a> from Github.</p>

<p>Patches and suggestions are very welcome!</p>