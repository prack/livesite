<?php

// TODO: Document!
class Livesite_Public
  implements Prack_I_MiddlewareApp
{
	// TODO: Document!
	public function call( &$env )
	{
		ob_start();
		  include( join( DIRECTORY_SEPARATOR, array( $env[ 'livesite.templates' ], 'public.html.php' ) ) );
		$output = ob_get_clean();
		
		return array( 200, array(), $output );
	}
}
