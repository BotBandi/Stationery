<?php
	$nav=file_get_contents('../html/'.$login);
	$output=str_replace('::nav',$nav,$output);
	$output=str_replace('::items',$content,$output);
	print($output);
	exit();