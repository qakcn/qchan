<?php
 /**
  * 这是简体中文的语言文件。
  */

// INFO
define('INFO_NAME', 'Qchan 图床');

// UI
define('UI_METHOD_NORMAL', '普通上传');
define('UI_METHOD_URL', 'URL上传');
define('UI_METHOD_DROP', '拖放上传');
define('UI_INFO_DROP', '<p>拖放上传现在已经不需要拖放到特定区域了<br>拖放图片文件到本页面的任何位置均可上传</p><p>拖放上传需要拖放API和文件API的支持，仅在如下浏览器中可用：</p><ul><li>Mozilla Firefox 3.6及更高版本</li><li>Microsoft Internet Explorer 10</li><li>Google Chrome 7及更高版本</li></ul>');
define('UI_INFO_URL', '将图片地址填入下面的输入框内，每行一个。');
define('UI_INFO_NORMAL', '使用浏览器基本上传功能。');
define('UI_TIPS_IS_THUMB', '启用缩略图');
define('UI_TIPS_IS_THUMB_YES', '是');
define('UI_TIPS_IS_THUMB_NO', '否');
define('UI_TIPS_THUMB_SIZE', '缩略图大小');
define('UI_TIPS_FILESIZE', '文件大小');
define('UI_SET_TIPS', '注意：设置仅当前会话有效，刷新或再次打开本页面后需要重新设置。');
define('UI_SET_IS_THUMB', '是否生成<span class="help" title="缩略图是一副较小的图片，并且在图片上有到原始图片的链接">缩略图</span>');
define('UI_SET_THUMB_SIZE', '缩略图<span class="help" title="缩略图大小是指最大大小，表示缩略图的大小不会超过这个范围，缩略图会保持原始宽高比，不会被裁剪、拉伸">大小</span>');
define('UI_SET_RESET', '重置');
define('UI_SET_FILESIZE', '允许上传的文件大小');
define('UI_SET_INFO_MIB', 'MiB是二进制进位的单位，1MiB=1024KiB、1KiB=1024B；而1MB=1000KB、1KB=1000B。');
define('UI_SET_SETICON', '设置');
define('UI_USER_TIPS', '注意事项');
define('UI_RESULT_TITLE', '上传结果');
define('UI_SUBMIT', '上传');
define('UI_CLEAR', '清空');
define('UI_GRAB', '隔墙抓图');
define('UI_ADD', '增加一个文件');
define('UI_REMOVE', '移除');
define('UI_NOMORE', '最多10个文件');
define('UI_RESULT_ORIG','<span class="help" title="这是到原始图片的URL">原始图片地址</span>');
define('UI_RESULT_ORIGBB','<span class="help" title="这是原始图片的BBCode">原始图片BBCode</span>');
define('UI_RESULT_THBB','<span class="help" title="这是带原始图片链接的缩略图BBCode">带链接的缩略图BBCode</span>');
define('UI_RESULT_ORIGHTML','<span class="help" title="这是原始图片的HTML代码">原始图片HTML</span>');
define('UI_RESULT_THHTML','<span class="help" title="这是带原始图片链接的缩略图HTML代码">带链接的缩略图HTML</span>');
define('UI_ERROR_TITLE', '文件上传出错！');
define('UI_ERROR_NAME', '<strong>文件名</strong>：<br>');
define('UI_MSG_WAIT', "请等待上一次上传完成！\n如果您上传的文件很大，需要花费的时间会较长。\n如果花费时间过长，请刷新本页后重试。");
define('UI_UPLOADING_TITLE',"上传文件");
define('UI_UPLOADING_INFO',"正在上传，请稍候！");

// ERR
define('ERR_UPLOAD_INI_SIZE', '文件大小超出php.ini限制');
define('ERR_UPLOAD_PARTIAL', '文件上传不完整');
define('ERR_UPLOAD_NO_FILE', '文件没有上传');
define('ERR_UPLOAD_NO_TMP_DIR', '临时目录不可用');
define('ERR_UPLOAD_CANT_WRITE', '无法写入文件');
define('ERR_UPLOAD_EXTENSION', '文件上传被PHP扩展阻止');
define('ERR_UPLOAD_FAIL_SAVE', '保存文件失败');
define('ERR_UPLOAD_FORM_SIZE', '文件大小超出限制');
define('ERR_UPLOAD_WRONG_TYPE', '不支持的文件格式');
define('ERR_UPLOAD_TOO_MANY', '文件太多，仅有前20个会被上传。');
define('ERR_NOT_URL', '不是合法的URL！');
define('ERR_NO_RESPONSE', '服务器无响应，请重试！');

// MANAGE
define('MANAGE_TITLE', 'Qchan 管理页');
define('MANAGE_USERNAME', '管理员用户名');
define('MANAGE_PASSWORD', '管理员密码');
define('MANAGE_LOGIN', '登录');
define('MANAGE_LOGGEDIN', '成功登录！');
define('MANAGE_INTRO', 'Qchan管理页');
define('MANAGE_FOLDER_YEAR', '年月');
define('MANAGE_NO_THUMB', '无缩略图');
define('MANAGE_DELETE', '删除');
define('MANAGE_DELETED', '文件删除成功');
define('MANAGE_GOBACK', '返回前页');
?>