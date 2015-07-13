<?php
	/**
	 * J. Mulle, for app, 11/12/14 7:46 PM
	 * www.introspectacle.net
	 * Email: this.impetus@gmail.com
	 * Twitter: @thisimpetus
	 * About.me: about.me/thisimpetus
	 */
	$response = array( 'orb_card_stage' => $this->Element( 'orbcard', array( 'content' => $orb, "ajax" => false ) ),
	                   'optflag_header' => $this->Element( 'optflag_filter_header', array( 'optflags' => $orb[ 'Orb' ][ 'Optflag' ] ) ),
	                   'orbopts_list'   => array('portionable' => $portionable,
	                                             'list' => $this->Element( 'orb_opts_list', array( 'orb' => $orb['Orb']))
		                   ) );
	echo json_encode( $response );