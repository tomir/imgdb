<?php
return array(	
	"base_url"   => url() . '/social/auth',
	"providers"  => array (
		"Google"     => array (
			"enabled"    => true,
			"keys"       => array ( "id" => "**************", "secret" => "**************" ),
			"scope"      => "https://www.googleapis.com/auth/userinfo.profile ".
                            "https://www.googleapis.com/auth/userinfo.email"   ,
			),
		"Facebook"   => array (
			"enabled"    => true,
			"keys"       => array ( "id" => "**************", "secret" => "**************" ),
			'scope'      =>  'email',
			),
		"Twitter"    => array (
			"enabled"    => true,
			"keys"       => array ( "key" => "**************", "secret" => "**************" )
			)
	),
);