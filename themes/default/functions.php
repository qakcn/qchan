<?php
function format_results($results) {
	$id = 0;
	$format = <<<FORMAT
<li id="n%d" draggable="true" style="width: %dpx; height: %dpx; margin-top: %dpx;" data-path="%s" data-thumb="%s"><div class="img" style="background-image: url(&quot;%s&quot;); background-size: %dpx %dpx; width: %dpx; height: %dpx;"><div class="progress" style="width: %dpx; height: %dpx; background-position: %dpx center;"><div class="select" style="padding-top: %dpx;"><p>Selected</p></div></div></div></li>
FORMAT;
	$output='';
	foreach($results as $result) {
		if(isset($result['width'])) {
			$width = $result['width'];
			$height = $result['height'];
		}else {
			$width = 200;
			$height = 200;
		}
		$output .= sprintf($format, $id++, $width, $height, (205-$height), $result['path'], $result['thumb'], $result['path'], $width, $height, $width, $height, $width, $height, $width, $height-30);
	}
$output.=<<<SCRIPT
<script type="application/javascript">
var first_load = document.createElement('div'),
add = document.createElement('a');
</script>
SCRIPT;
	return $output;
}

function format_script($results) {
	$id = 0;
	$format = <<<FORMAT
if(!n%d) {
	n%d = document.getElementById('n%d');
}
n%d.onclick = toggleinfo();
n%d.oncontextmenu = toggleinfo();
n%d.work = %s;
FORMAT;
	$output = '';
	foreach($results as $result) {
		$work = json_encode(array('name'=>$result['name'], 'path'=>$result['path'], 'thumb'=>$result['thumb'], 'status'=>'success', 'qid'=>'n'.$id));
		$output .= sprintf($format, $id, $id, $id, $id, $id, $id, $work);
		$id++;
	}
	return $output;
}
