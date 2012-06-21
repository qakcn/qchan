<?php
 /**
  * This is configuration file
  */

// Site information
define('SITE_TITLE', 'Qchan');
define('SITE_DESCRIPTION', 'Uploads & Hosts, All Your Images');
define('SITE_KEYWORDS', 'images, photos, image hosting, photo hosting, free image hosting');
define('SITE_HOMEPAGE', ''); // If your image hosting site is not homepage, set this for your homepage's name.
define('SITE_HOMEPAGE_URL', ''); // This is the URL for the homepage above. Not work if SITE_HOMEPAGE is empty ('').
define('SITE_HOMEPAGE_DESC', ''); // This is the description for the homepage above. Not work if SITE_HOMEPAGE is empty ('').
define('USER_TIPS', 'No Abuse Use!'); // This information is show in the top, set to empty ('') to hide. Using HTML code is acceptable.

// Site language
define('LANG', 'zh-cn'); // Set to 'en' for English, 'zh-cn' for Simplified Chinese.

// Admin information
define('ADMIN_NAME', 'admin');
define('ADMIN_PASSWORD', 'admin888');

// Upload file size limit
define('SIZE_LIMIT', 2); // In megabyte

// Thumbnail size limit
define('IS_THUMB', false); // Will it generate thumbnails or not, true or false.
define('ALLOW_USER_CHANGE_IS_THUMB', false); // Set to true if you want user changing thumbnails option
define('THUMB_MAX', 500); // Max size of thumbnail, in pixel
define('THUMB_MIN', 100); // Min size of thumbnail, in pixel
define('THUMB_DEFAULT', 200); // Default size of thumbnail, in pixel

// Timezone
define('TIMEZONE', 'UTC');

// Set where to save the files
define('UPLOAD_DIR', 'uploads');
define('THUMB_DIR', 'thumbs');

// Where do you visit your site. For example, 'http://www.example.com/' is '/', 'http://www.example.com/qchan/' is '/qchan/', 'http://www.example.com/q/qchan/' is '/q/qchan/'
define('VISIT_ROOT', '/qchan/');

?>