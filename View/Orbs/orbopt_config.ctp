<?php
/**
 * J. Mulle, for app, 5/4/15 6:16 PM
 * www.introspectacle.net
 * Email: this.impetus@gmail.com
 * Twitter: @thisimpetus
 * About.me: about.me/thisimpetus
 */
//db($orb);
echo $this->element('vendor_ui/orbopt_config', array('orb' => $orb,
                                                     'orbopts' => $orbopts,
                                                     'default_opts' => $default_opts,
                                                     'optflags' => $optflags,
                                                     'orbcats' => $orbcats,
                                                     'orbopts_groups' => $orbopts_groups,
                                                     'active_orbopts' => $active_orbopts
	));