<?php

include_once 'cors.php';

// for the app
if(isset($_POST['test']) && $_POST['test'] == 'Correct?') {
	echo 'LFSE reached!';
}