<?php

/* Deny direct visit */
if(!defined('INDEX_RUN')) {
	header('HTTP/1.1 403 Forbidden');
	exit('This file must be loaded in flow.');
}

?>

<!-- Footer -->
<footer id="main_footer">
<p class="hide_mobile"><?=COPYRIGHT ?></p>
<p class="hide_mobile"><?=__('This site is powered by <a target="_blank" href="%s">Qchan %s</a>', QCHAN_URL, QCHAN_VER) ?></p>
<p><a href="?page=agreement" target="_blank"><?=__('Agreement') ?></a>&nbsp;|&nbsp;<a href="javascript:void(0);" id="help"><?=__('Help') ?></a>&nbsp;|&nbsp;<a href="?page=privacy" target="_blank"><?=__('Privacy') ?></a>&nbsp;|&nbsp;<a href="mailto:<?=ADMIN_EMAIL ?>?subject=<?=__('Report%20Abuse%20Use') ?>&body=<?=__('List%20image%20address%20here%20for%20report.') ?>" title="<?=__('Report abuse use such as piracy, pornography, extreme violence, religious discrimination and racism, etc.') ?>"><?=__('Report Abuse') ?></a></p>
<?=format_main_site() ?>
</footer>
</body>
</html>