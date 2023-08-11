<?php
$folder = stripcslashes($_GET['q']);
$zipfile = md5($folder).'.zip';
set_time_limit(0);
@unlink($zipfile);

class FlxZipArchive extends ZipArchive {
	/** Add a Dir with Files and Subdirs to the archive;;;;; @param string $location Real Location;;;;  @param string $name Name in Archive;;; @author Nicolas Heimann;;;; @access private  **/
	public function addDir($location, $name) {
		$this->addEmptyDir($name);

		$this->addDirDo($location, $name);
	} // EO addDir;

	/**  Add Files & Dirs to archive;;;; @param string $location Real Location;  @param string $name Name in Archive;;;;;; @author Nicolas Heimann
	 * @access private   **/
	private function addDirDo($location, $name) {
		$name .= '/';
		$location .= '/';
		// Read all Files in Dir
		$dir = opendir ($location);
		while ($file = readdir($dir)) {
			if ($file == '.' || $file == '..') continue;
			// Rekursiv, If dir: FlxZipArchive::addDir(), else ::File();
			$do = (filetype( $location . $file) == 'dir') ? 'addDir' : 'addFile';
			$this->$do($location . $file, $name . $file);
		}
	} // EO addDirDo();
}

$za = new FlxZipArchive;
$res = $za->open($zipfile, ZipArchive::CREATE);
if($res === TRUE) {
	$za->addDir($folder, basename($folder));
	$za->close();
} else {
	echo 'Could not create a zip archive';
}

ob_get_clean();
header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private", false);
header("Content-Type: application/zip");
header("Content-Disposition: attachment; filename=" . basename($zipfile) . ";" );
header("Content-Transfer-Encoding: binary");
header("Content-Length: " . filesize($zipfile));
readfile($zipfile);

?>