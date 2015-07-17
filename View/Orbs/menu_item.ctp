<?php
	/**
	 * J. Mulle, for app, 11/12/14 7:46 PM
	 * www.introspectacle.net
	 * Email: this.impetus@gmail.com
	 * Twitter: @thisimpetus
	 * About.me: about.me/thisimpetus
	 */
		$response = array( 'orb_card_stage' => $this->Element( 'orbcard/orbcard', array( 'content' => $orb, "ajax" => false ) ),
		                   'optflag_header' => $this->Element( 'orbcard/optflag_filter_header', array( 'optflags' => $orb[ 'Orb' ][ 'Optflag' ] ) ),
		                   'orbopts_list'   => array('portionable' => $portionable,
		                                             'list' => ""
			                   ) );
		foreach ($orb['Orb']['Orbopt'] as $opt) $response['orbopts_list']['list'] .= $this->Element("orbcard/opt_row", array("opt" => $opt));

		echo json_encode( $response );