<?php

// Set display language
define('UI_LANG','en');
define('UI_THEME','default');

// Site information
define('SITE_TITLE', 'Qchan Image Host');
define('SITE_DESCRIPTION', 'Upload & Share');
define('SITE_KEYWORDS', 'images, photos, image hosting, photo hosting, free image hosting');
define('ADMIN_EMAIL', 'admin@example.com');
// Main site is set for parent site
define('MAIN_SITE', false);
define('MAIN_SITE_NAME', '');
define('MAIN_SITE_LOGO', '');
define('MAIN_SITE_URL', '');

// Upload settings
define('SIZE_LIMIT', '4M');
define('UPLOAD_DIR', 'uploads');
define('THUMB_DIR', 'thumbs');

// Management Settings
define('MANAGE_NAME','admin');
define('MANAGE_PASSWORD','admin');

// CDN list settings
define('CDN_ENABLED', false); // use CDN in image URL if true
define('CDN_LIST', 'a.example.com,b.example.com,c.example.com'); // list of CDN domain name or IP address to use, separate by comma, not support IDN, use punycode domain name (start with "xn--") instead
define('CDN_HTTPS', 'both'); // true if CDN uses HTTPS, false for not, or a string separate by comma to set for each domain in CDN_LIST
define('CDN_PORTS_HTTP', ''); // TCP port that CDN uses, leave blank for default, or a string separate by comma to set for each domain in CDN_LIST
define('CDN_PORTS_HTTPS', ''); // familiar to above, but only work on HTTPS

// Copyright statement
define('COPYRIGHT', 'Every uploaded image must licensed under <a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/4.0/">Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International License</a>.');

// Watermark settings
define('WATERMARK', false);
define('WATERMARK_MIN_SIZE', '200x200'); // Only mark images those are larger than
define('WATERMARK_POS', '10,10'); //Watermark position coordinates, positive refer to left-top of image, negative refer to right-bottom
