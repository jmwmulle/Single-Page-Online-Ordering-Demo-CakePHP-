<?php
/**
 * J. Mulle, for app, 5/1/15 10:53 AM
 * www.introspectacle.net
 * Email: this.impetus@gmail.com
 * Twitter: @thisimpetus
 * About.me: about.me/thisimpetus
 */

if ( array_key_exists("replace", $response) ) {
	$response['replace'] = $this->element($response['replace']['element'], $response['replace']['options']);
}
echo json_encode($response);
