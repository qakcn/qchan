<?php
session_start();

function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

$time_start = microtime_float();
/**
 * This is a simple manage page for Qchan
 */
if (!ini_get('display_errors')) {
    ini_set('display_errors', 1);
}

// Load configurations
require './config.php';

// Load language file
require './lang/' . LANG . '.php';

// Some system settings
define('QCHAN_VER', '0.2');
if(function_exists('date_default_timezone_set'))
	date_default_timezone_set(TIMEZONE);
	
$page=isset($_GET['page'])?$_GET['page']:1;

function get_files($path) {
	$hash = md5($path);
	if(isset($_SESSION[$hash])) {
		return $_SESSION[$hash];
	}else {
		$files = array();
		$dh = opendir($path);
		while (($file = readdir($dh)) !== false) {
			if($file != '.' && $file != '..') {
				$filepath = "$path/$file";
   			$files[$file] = filemtime($filepath);
   		}
		}
		closedir($dh);
		arsort($files);
		$filem=array();$i=0;
		foreach($files as $key => $value) {
			$filem[$i++] = $key;
		}
		$_SESSION[$hash]=$filem;
		return $filem;
	}
}
?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title><?php echo MANAGE_TITLE; ?></title>
<style type="text/css">
h1{margin: 5px;font-size:25px;}
table tr {text-align:center;vertical-align:bottom;}
table tr td {border:1px solid gray;width:200px;}
table tr {text-align:center;vertical-align:bottom;}
table tr td img {border:1px solid gray;max-width:200px;}
input#gotopagenum {width: 50px;text-align: right;}
ul#yearmonth{margin:10px;padding:0;font-size:14px;}
ul#yearmonth h2{padding-right: 22px;}
ul#yearmonth li,ul#yearmonth ul{margin:0;padding:0;display:inline-block;white-space: nowrap;}
.ex_year{display:inline-block;height:20px;width:64px;background-image: url(site-img/anchor_y.png);background-repeat: no-repeat;text-align: center;margin-left: -12px;padding-top:5px;cursor:pointer;color:blue;}
.ex_year:hover,.thisyear{background-position: 0 -25px;color:orangered;}
.ex_month{display:inline-block;height:20px;width:55px;background-image: url(site-img/anchor_m.png);background-repeat: no-repeat;text-align: center;margin-left: -9px;padding-top:5px;cursor:pointer;text-decoration: none;color:blue;}
.ex_month:hover,.thismonth{background-position: 0 -25px;color:orangered;}
</style>
<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.7.min.js"></script>
<script type="text/javascript" src="jquery.scrollLoading-min.js"></script>
<script type="text/javascript" >
$(function(){
	$('.scrollLoading').scrollLoading();
	$('span#page').click(function(){$(this).hide().after('<input type="text" value="'+$(this).html()+'" id="gotopagenum"><input type="button" value="GO" id="gotopage">');$('input#gotopagenum').select();});
	$('input#gotopagenum').live('click',function(){$(this).select();});
	$('input#gotopage').live('click',function(){
		if(Number($('input#gotopagenum').val())>Number($('span#maxpage').html())) {
			alert('Too Many Pages!');
		}else {
			window.location.href='manage.php?year=<?=isset($_GET['year'])?$_GET['year']:'0' ?>&month=<?=isset($_GET['month'])?$_GET['month']:'0' ?>&page='+Number($('input#gotopagenum').val());
		}
	});
	$('a#prevpage').click(function(){
		page=Number($('span#page').html())-1;
		if(page==0) {
			alert('No Page 0!');
		}else{
			window.location.href='manage.php?year=<?=isset($_GET['year'])?$_GET['year']:'0' ?>&month=<?=isset($_GET['month'])?$_GET['month']:'0' ?>&page='+page;
		}
	});
	$('a#nextpage').click(function(){
		page=Number($('span#page').html())+1;
		if(page>Number($('span#maxpage').html())) {
			alert('Too Many Pages!');
		}else{
			window.location.href='manage.php?year=<?=isset($_GET['year'])?$_GET['year']:'0' ?>&month=<?=isset($_GET['month'])?$_GET['month']:'0' ?>&page='+page;
		}
	});
	$('span.ex_year').click(function(){
		if(!$(this).hasClass('thisyear')){
			$('.thisyear').removeClass('thisyear').parent().children('ul').hide();
			$(this).addClass('thisyear').parent().children('ul').show();
		}
		
	});
});
</script>
</head>
<body>
<h1><?php echo MANAGE_INTRO; ?></h1>
<?php
if((!isset($_COOKIE['login']) or $_COOKIE['login'] != ADMIN_NAME) and (!isset($_GET['login']) or $_GET['login'] != 'login')) {
?>
<form method="post" action="?login=login">
<p><label><?php echo MANAGE_USERNAME; ?></label><input type="text" name="adminname"></p>
<p><label><?php echo MANAGE_PASSWORD; ?></label><input type="password" name="adminpwd"></p>
<p><input type="submit" name="submit" value="<?php echo MANAGE_LOGIN; ?>"></p>
</form>
<?php
}elseif(isset($_GET['login']) and $_GET['login'] == 'login') {
	if($_POST['adminname'] == ADMIN_NAME and $_POST['adminpwd'] == ADMIN_PASSWORD) {
		echo '<script>document.cookie="login=' . ADMIN_NAME . '";</script>';
		echo '<p>' . MANAGE_LOGGEDIN . ' <a href="manage.php">' . MANAGE_GOBACK . '</a></p>';
	}
}elseif(isset($_GET['delete']) and $_GET['delete'] != '') {
	$delsuc=true;
	$checksum=md5(file_get_contents(UPLOAD_DIR . '/' . $_GET['year'] . '/' . $_GET['month'] . '/' . $_GET['delete']));
	$delsuc &= unlink(UPLOAD_DIR . '/hash/' . $checksum);
	if(file_exists(THUMB_DIR . '/' . $_GET['year'] . '/' . $_GET['month'] . '/' . $_GET['delete']))
		$delsuc &= unlink(THUMB_DIR . '/' . $_GET['year'] . '/' . $_GET['month'] . '/' . $_GET['delete']);
	if(file_exists(UPLOAD_DIR . '/' . $_GET['year'] . '/' . $_GET['month'] . '/' . $_GET['delete']))
		$delsuc &= unlink(UPLOAD_DIR . '/' . $_GET['year'] . '/' . $_GET['month'] . '/' . $_GET['delete']);
	if($delsuc)
		echo '<h3>' . MANAGE_DELETED . '</h3>';
	
}elseif(isset($_GET['clearwork']) and $_GET['clearwork'] == 'clear') {
	shell_exec('rm -f '.UPLOAD_DIR.'/working/*');
	echo '<h3>Success clearing working folder</h3>';
}else {
?>
<ul id="yearmonth"><li><h2><?php echo MANAGE_FOLDER_YEAR; ?></h3></li><?php
$years = scandir(UPLOAD_DIR);

foreach($years as $year){
	if($year != '.' && $year != '..' && $year != 'working' && $year != 'hash') {
		if(!isset($_GET['year']) || $_GET['year']!=$year) {$style='style="display:none;"';$thisyear='';}
		else {$thisyear='thisyear';$style='';}
		echo '<li><span class="ex_year '.$thisyear.'">'.$year.'</span>';
		$months = scandir(UPLOAD_DIR . '/' . $year);
		
		echo '<ul id="month_'.$year.'" '.$style.'>';
		foreach($months as $month){
			if($month != '.' && $month != '..') {
				if(isset($_GET['month']) && $_GET['year']==$year && $_GET['month']==$month) {$thismonth='thismonth';}
				else {$thismonth='';}
				echo '<li><a class="ex_month '.$thismonth.'" href="manage.php?year='.$year.'&month='.$month.'">'.$month.'</a></li>';
			}
		}
		echo '</ul></li>';
	}
}
?><li>&nbsp;&nbsp;<a target="_blank" href="manage.php?clearwork=clear">Clear Working</a></li></ul>
<?php if(isset($_GET['year']) and isset($_GET['month']) and $_GET['year'] != '' and $_GET['month'] != '') { ?>
<table><tbody><tr><?php
$files = get_files('./'.UPLOAD_DIR . '/' . $_GET['year'] . '/' . $_GET['month']);
for($i = ($page - 1) * 192; $i < $page * 192 && $i < count($files); $i++){
	$file=$files[$i];
	if(file_exists(UPLOAD_DIR . '/' . $_GET['year'] . '/' . $_GET['month'] . '/' . $file)) {
		echo '<td><p><a target="_blank" href="' . UPLOAD_DIR . '/' . $_GET['year'] . '/' . $_GET['month'] . '/' . rawurlencode($file) . '">';
		if(file_exists(THUMB_DIR . '/' . $_GET['year'] . '/' . $_GET['month'] . '/' . $file)) {
			echo '<img class="scrollLoading" src="site-img/px.gif" data-url="' . THUMB_DIR . '/' . $_GET['year'] . '/' . $_GET['month'] . '/' . rawurlencode($file) . '"></a></p>';
		}else {
			echo '<img class="scrollLoading" src="site-img/px.gif" data-url="' . UPLOAD_DIR . '/' . $_GET['year'] . '/' . $_GET['month'] . '/' . rawurlencode($file) . '"><br>' . MANAGE_NO_THUMB . '</a></p>';
		}
		echo '<a target="_blank" href="manage.php?year=' . $_GET['year'] . '&month=' . $_GET['month'] . '&delete=' . rawurlencode($file) . '">' . MANAGE_DELETE . '</a></p></td>';
	}else {
		echo '<td>DELETED!</td>';
	}
	if($i % 6 == 5) echo '</tr><tr>';
}
while($i % 6 != 0) {	echo "<td></td>";$i++;}
?></tr></tbody>
<?php
	echo '<tbody><tr><td><a href="javascript:void(0)" id="prevpage">上一页</a></td><td colspan="4">第<span id="page">'.$page.'</span>页/共<span id="maxpage">'.ceil(count($files)/192).'</span>页</td><td><a href="javascript:void(0)" id="nextpage">下一页</a></td></tr></tbody>';
?>
</table>
<?php
	}
}
echo '<p>exec in sec ' . (microtime_float()-$time_start) . '</p>';
?>

</body>
</html>