<?php

// TODO: Document!
class Sandbox_Public
  implements Prack_Interface_MiddlewareApp
{
	// TODO: Document!
	public function call( $env )
	{
		$user = $env->get( 'REMOTE_USER' );
		
		ob_start();
		  $templates = $env->get( 'sandbox.templates' );
		  include( join( DIRECTORY_SEPARATOR, array( $templates->raw(), 'public.html.php' ) ) );
		$output = Prb::_String( ob_get_clean() );
		
		return Prb::_Array( array(
		  Prb::_Numeric( 200 ),
		  Prb::_Hash(),
		  Prb::_Array( array( $output ) )
		) );
	}
}
