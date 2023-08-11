<?php
session_start();
if(!isset($_SESSION['LFSE_PASS'])) {
	include_once 'pass.php';
	exit;
}
// remove / at the end
$favorites = array(
	'C:\Desktop',
	'C:\Downloads',
	'D:\Photos',
	'D:\Videos',
	//'I:',
);

if(isset($_GET['dir']) && is_dir($_GET['dir'])) {
	$dir = $_GET['dir'];
} else {
	$dir = 'C:/';
}

$crumbs = explode('/', $dir);
unset($crumbs[count($crumbs)-1]);

$folders = array();
$files = array();
//foreach (glob($dir.'*') as $filename) {
$list = scandir(realpath($dir));
foreach($list as $filename) {
	if($filename == '.' || $filename == '..') continue;
	$filename = $dir.$filename;
	$name = explode('/',$filename);
	$name = $name[count($name)-1];
	if(is_dir($filename)) {
		$folders[] = array($filename, $name);
	} else {
		$files[] = array($filename, $name);
	}
}

function urlesc($str) {
	return urlencode(esc($str));
}
function esc($str) {
	return htmlentities(str_replace("'", "\'", $str));
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, minimum-scale=1.0, initial-scale=1.0, user-scalable=no">
<title><?=$dir?></title>
<link rel="stylesheet" href="css/md-css.min.css">
<link rel="stylesheet" href="css/md-icons.min.css">
<style>
[fslist] div:hover {
	background: #dfe;
	color: black;
	cursor: pointer;
}
[fscrumbs] {
	position: absolute;
	top: 56px;
	background: #fff;
	left: 0;
	right: 0;
	padding-left: 8px;
	line-height: 32px;
	box-shadow: 0 2px 5px rgba(0,0,0,.25);
}
[material] [main] [content] {
	margin-top: 96px;
}
</style>
</head>
<body material fluid>
  <div panel id="panel1" fullheight>
    <div drawer bg-blue-grey>
      <div list>
				<small bold>Drives</small>
        <a item href="index.php">
          <span icon="home"></span>
          C:/
        </a>
        <a item href="index.php?dir=D:/">
          <span icon="laptop"></span>
          D:/
        </a>
        <a item href="index.php?dir=I:/">
          <span icon="lock"></span>
          I:/
        </a>
				<small bold>Favourites</small>
				<?php
					foreach($favorites as $item) {
						$item = str_ireplace('\\', '/', $item);
						$item_x = explode('/', $item);
						?>
							<a item href="index.php?dir=<?=urlesc($item)?>/"><span icon="favorite"></span><?=$item_x[count($item_x)-1]?></a>
						<?php
					}
				?>
				<hr>
				<div item>
					<a href="pass.php" centered bg-black button bold>LOGOUT</a>
				</div>
      </div>
    </div>
    <div main>
      <div toolbar seamed bg-blue-grey900>
        <span left ripple panel-target="panel1">
          <span icon="menu"></span>
        </span>
        <header title>
				<!--?php
					$c = '';
					foreach($crumbs as $d) {
						$c .= $d.'/';
						echo '<a fg-white href="?dir='.urlesc($c).'">'.$d.'</a>/';
					}
				?-->
				LAN File System Explorer
				</header>
				<span right>
					<a href="zip.php?q=<?=urlesc($dir)?>" fg-white bg-orange800 style='float: right; padding: 0 8px !important'>
					Download Folder as ZIP <span icon="file-download"></span>
					</a>
				</span>
				<div bg-blue-grey900 fg-orange fscrumbs bold>
					<?php
						$c = '';
						foreach($crumbs as $d) {
							$c .= $d.'/';
							echo '<a fg-cyan200 href="?dir='.urlesc($c).'"> '.$d.' </a> / ';
						}
					?>
				</div>
      </div>
      <div content>
        <div fluid card fslist>
					<?php
					foreach($folders as $item) {
						?>
							<div /*onclick="this.hasAttribute('selected')?this.removeAttribute('selected'):this.setAttribute('selected', true)"*/
							     onclick="top.location.href='?dir=<?=urlesc($item[0])?>/'"
									 oncontextmenu="filePopup('<?=esc($item[0])?>'); return false">
								<span icon="folder-open"></span>
								<?=$item[1]?>
							</div>
						<?php
					}
					foreach($files as $item) {
						?>
							<div onclick="top.location.href='file.php?q=<?=urlesc($item[0])?>'">
									 <!--oncontextmenu="filePopup('<?=esc($item[0])?>'); return false"-->
								<span icon="insert-drive-file"></span>
								<?=$item[1]?>
								<span bold style='float: right; font-size: 12px'><a onclick='function(e){e.preventPropagation()}' href='download.php?q=<?=urlesc($item[0])?>' fg-orange><span icon='file-download'></span> DOWNLOAD</a></span>
							</div>
						<?php
					}
					?>
        </div>
				<!--div card id='context' popup z-1 style='position: fixed'>
					file context here!
				</div-->
      </div>
    </div>
  </div>
	<script>
		function filePopup(f) {
			var e = window.event;
			var x = e.x || e.clientX;
			var y = e.y || e.clientY;
			//console.log(e);
			//$('#context').css({left: x, top: y}).show();
			return false;
		}
		window.addEventListener('click', function(e) {
			try{
				if(e.which != 3) {
					$('#context').hide();
				}
			} catch(e) {
				if(e.button != 2) {
					$('#context').hide();
				}
			}
		});
	</script>
  <script src="js/zepto.min.js"></script>
  <script src="js/velocity.min.js"></script>
  <script src="js/md-js.min.js"></script>
</body>
</html>