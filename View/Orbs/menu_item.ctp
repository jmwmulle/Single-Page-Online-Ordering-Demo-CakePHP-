<?php
	/**
	 * J. Mulle, for app, 11/12/14 7:46 PM
	 * www.introspectacle.net
	 * Email: this.impetus@gmail.com
	 * Twitter: @thisimpetus
	 * About.me: about.me/thisimpetus
	 */
	$response = ["success" => true,
	             "error" => false,
	             "delegate_route" => "configure_orb".DS.$submitted_data['id'],
	             "data" => ["submitted_data" => $submitted_data,
	                        'view_data' => [
		                        'orb_card_stage' => $this->Element( 'orbcard/orbcard', [ 'content' => $orb, "ajax" => false ] ),
	                            'optflag_header' => $this->Element( 'orbcard/optflag_filter_header', [ 'optflags' => $orb[ 'Orb' ][ 'Optflag' ] ] ),
	                            'orbopts'   => ['portionable' => $portionable, 'list' => "" ],
		                   ]
	             ]
	];
	try {
		foreach ($orb['Orb']['Orbopt'] as $opt) $response['data']['view_data']['orbopts']['list'] .= $this->Element("orbcard/opt_row", array("opt" => $opt));
	} catch (Exception $e) {
		$response['success'] = false;
		$response['error']  = $e->getMessage();
	}

	echo json_encode( $response );