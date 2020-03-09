<?php

/**
 * Simple helper to debug to the console
 *
 * @param $data object, array, string $data
 * @param $context string  Optional a description.
 *
 * @return string
 */
function d($data, $context = 'Debug in Console')
{
	// Buffering to solve problems frameworks, like header() in this and not a solid return.
	ob_start();

	$output = "";
	// $output  = 'console.info(\'' . $context . ':\');';
	$output .= 'console.log(' . json_encode($data) . ');';
	$output  = sprintf('<script>%s</script>', $output);

	echo $output;
}