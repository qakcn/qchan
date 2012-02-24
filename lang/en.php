<?php
 /**
  * This is the English language file.
  */

// INFO
define('INFO_NAME', 'Qchan Image Hosting');

// UI
define('UI_METHOD_NORMAL', 'NORMAL UPLOAD');
define('UI_METHOD_URL', 'URL UPLOAD');
define('UI_METHOD_DROP', 'DRAG & DROP UPLOAD');
define('UI_INFO_DROP', '<p>You can now drop files everywhere on this page instead of special area.</p><p>Drag & Drop Uploading uses HTML5 drag and drop API and file reader API, works only in the browsers list below.</p><ul><li>Mozilla Firefox 3.6 and higher version.</li><li>Microsoft Internet Explorer 10.</li><li>Google Chrome 7 and higher version.</li></ul>');
define('UI_INFO_URL', 'Please enter picture URLs below, each URL one line.');
define('UI_INFO_NORMAL', 'Use the traditinal no AJAX upload.');
define('UI_TIPS_IS_THUMB', 'Generate Thumbnails');
define('UI_TIPS_IS_THUMB_YES', 'Yes');
define('UI_TIPS_IS_THUMB_NO', 'No');
define('UI_TIPS_THUMB_SIZE', 'Thumbnails Size');
define('UI_TIPS_FILESIZE', 'File Size');
define('UI_SET_TIPS', 'WARNING：These settings only work in current session. Refreshing or reloading page will cause settings resetting!');
define('UI_SET_IS_THUMB', 'Generate <span class="help" title="A thumbnail is smaller size of picture used for preview.">thumbnails</span> or not');
define('UI_SET_THUMB_SIZE', 'Thumbnails <span class="help" title="This is the maximum size of width or height of a thumbnail. Thumbnails will keep original aspect ratio.">size</span>');
define('UI_SET_RESET', 'Reset');
define('UI_SET_FILESIZE', 'File Size Limit');
define('UI_SET_INFO_MIB', 'MiB is binary prefix of file size unit, 1MiB=1024KiB, 1KiB=1024B. However 1MB=1000KB, 1KB=1000B.');
define('UI_SET_SETICON', 'Settings');
define('UI_USER_TIPS', 'Tips');
define('UI_RESULT_TITLE', 'RESULTS');
define('UI_SUBMIT', 'Upload');
define('UI_CLEAR', 'Clear');
define('UI_GRAB', 'Grab through GFW');
define('UI_ADD', 'Add one more file');
define('UI_REMOVE', 'Remove');
define('UI_NOMORE', 'No more than 10 files');
define('UI_PREVIEW', 'Open original picture in new window');
define('UI_RESULT_ORIG','<span class="help" title="This is URL to original picture">Original URL</span>');
define('UI_RESULT_ORIGBB','<span class="help" title="This is BBCode to original picture">Original BBCode</span>');
define('UI_RESULT_THBB','<span class="help" title="This is BBCode to thumbnail with link to original picture">Thumbnail BBCode link</span>');
define('UI_RESULT_ORIGHTML','<span class="help" title="This is HTML code to original size picture">Original HTML</span>');
define('UI_RESULT_THHTML','<span class="help" title="This is HTML code to thumbnail with link to original picture">Thumbnail HTML link</span>');
define('UI_ERROR_TITLE', 'Upload Error');
define('UI_ERROR_NAME', '<strong>File name</strong>: <br>');
define('UI_MSG_WAIT', "Wait for previous uploading finished!\nWhen you are uploading big picture, it will take more time.\nIf it takes too much time, please refresh this page and retry.");
define('UI_UPLOADING_TITLE',"Uploading");
define('UI_UPLOADING_INFO',"Uploading now, please have a cup of tea!");

// ERR
define('ERR_UPLOAD_INI_SIZE', 'File size exceeds php.ini settings.');
define('ERR_UPLOAD_PARTIAL', 'File was only partially uploaded.');
define('ERR_UPLOAD_NO_FILE', 'No file was uploaded.');
define('ERR_UPLOAD_NO_TMP_DIR', 'Missing a temporary folder.');
define('ERR_UPLOAD_CANT_WRITE', 'Failed to write file to disk.');
define('ERR_UPLOAD_EXTENSION', 'A PHP extension stopped the file upload.');
define('ERR_UPLOAD_FAIL_SAVE', 'Failed to save file to upload directory.');
define('ERR_UPLOAD_FORM_SIZE', 'File size exceeds size limit');
define('ERR_UPLOAD_WRONG_TYPE', 'Unupport filetype');
define('ERR_UPLOAD_TOO_MANY', 'Too many files, only first 20 will upload');
define('ERR_NOT_URL', 'This is not a legal URL!');
define('ERR_NO_RESPONSE', 'No response from server, Please try again!');

// MANAGE
define('MANAGE_TITLE', 'Qchan Manage');
define('MANAGE_USERNAME', 'Admin Username');
define('MANAGE_PASSWORD', 'Admin Psssword');
define('MANAGE_LOGIN', 'Login');
define('MANAGE_LOGGEDIN', 'Logged in!');
define('MANAGE_INTRO', 'Manage Page for Qchan');
define('MANAGE_FOLDER_YEAR', 'Years & Months');
define('MANAGE_NO_THUMB', 'No Thumbnail');
define('MANAGE_DELETE', 'Delete');
define('MANAGE_DELETED', 'File Deleting Successful');
define('MANAGE_GOBACK', 'Go back to previous page');
?>