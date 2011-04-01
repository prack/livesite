<?php

// TODO: Document!
class Livesite_Admin
  implements Prack_I_MiddlewareApp
{
	private $body;
	
	// TODO: Document!
	public function call( &$env )
	{
		$user = $env[ 'REMOTE_USER' ];
		
		ob_start();
		  include( join( DIRECTORY_SEPARATOR, array( $env[ 'livesite.templates' ], 'admin.html.php' ) ) );
		$output = ob_get_clean();
		
		return array( 200, array(), $output );
	}
}