<?php
    $file = $_GET['file'];
    header('content-type:application/'.end(explode('.',$file)));
    Header("Content-Disposition: attachment; filename=" . $file); //to set download filename
    exit(file_get_contents($file));
?>