<?php

// TODO: Document!
class Sandbox_Public
  implements Prack_I_MiddlewareApp
{
	// TODO: Document!
	public function call( &$env )
	{
		ob_start();
		  include( join( DIRECTORY_SEPARATOR, array( $env[ 'sandbox.templates' ], 'public.html.php' ) ) );
		$output = ob_get_clean();
		
		return array( 200, array(), $output );
	}
}
