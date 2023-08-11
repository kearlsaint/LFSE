<?php
$file = stripcslashes($_GET['q']);
ob_end_flush();
set_time_limit(0);
if (file_exists($file)) {
	// open the file in a binary mode
	/*$fp = fopen($file, 'rb');

	// send the right headers
	header("Content-Type: image/png");
	header("Content-Length: " . filesize($file));

	// dump the picture and stop the script
	fpassthru($fp);
	exit;*/
	/*header("X-Sendfile: $file");
header("Content-type: application/octet-stream");
header('Content-Disposition: attachment; filename="' . basename($file) . '"');*/

	//echo file_get_contents($file);

/*

	$filesize = filesize($file);

	$offset = 0;
	$length = $filesize;

	if ( isset($_SERVER['HTTP_RANGE']) ) {
			// if the HTTP_RANGE header is set we're dealing with partial content

			$partialContent = true;

			// find the requested range
			// this might be too simplistic, apparently the client can request
			// multiple ranges, which can become pretty complex, so ignore it for now
			preg_match('/bytes=(\d+)-(\d+)?/', $_SERVER['HTTP_RANGE'], $matches);

			$offset = intval($matches[1]);
			$length = intval($matches[2]) - $offset;
	} else {
			$partialContent = false;
	}

	$file = fopen($file, 'r');

	// seek to the requested offset, this is 0 if it's not a partial content request
	fseek($file, $offset);

	$data = fread($file, $length);

	fclose($file);

	if ( $partialContent ) {
			// output the right headers for partial content

			header('HTTP/1.1 206 Partial Content');

			header('Content-Range: bytes ' . $offset . '-' . ($offset + $length) . '/' . $filesize);
	}

	// output the regular HTTP headers
	header('Content-Type: ' . $ctype);
	header('Content-Length: ' . $filesize);
	header('Content-Disposition: attachment; filename="' . $fileName . '"');
	header('Accept-Ranges: bytes');

	// don't forget to send the data too
	print($data);*/
	
	
	
	
	$path = $file;
	
	
// Determine file mimetype
$finfo = new finfo(FILEINFO_MIME);
$mime = $finfo->file($path);
 
// Set response content-type
header('Content-type: ' . $mime);
 
// File size
$size = filesize($path);
 
// Check if we have a Range header
if (isset($_SERVER['HTTP_RANGE'])) {
    // Parse field value
    list($specifier, $value) = explode('=', $_SERVER['HTTP_RANGE']);
 
    // Can only handle bytes range specifier
    if ($specifier != 'bytes') {
        header('HTTP/1.1 400 Bad Request');
        return;
    }
 
    // Set start/finish bytes
    list($from, $to) = explode('-', $value);
    if (!$to) {
        $to = $size - 1;
    }
 
    // Response header
    header('HTTP/1.1 206 Partial Content');
    header('Accept-Ranges: bytes');
 
    // Response size
    header('Content-Length: ' . ($to - $from));
 
    // Range being sent in the response
    header("Content-Range: bytes {$from}-{$to}/{$size}");
 
    // Open file in binary mode
    $fp = fopen($path, 'rb');
    $chunkSize = 8192; // Read in 8kb blocks
 
    // Advance to start byte
    fseek($fp, $from);
 
    // Send the data
    while(true){
        // Check if all bytes have been sent
        if(ftell($fp) >= $to){
            break;
        }
 
        // Send data
        echo fread($fp, $chunkSize);
 
        // Flush buffer
        ob_flush();
        flush();
    }
} else {
    // If no Range header specified, send everything
    header('Content-Length: ' . $size);
 
    // Send file to client
    readfile($path);
}
	
	
	
	
	


}
