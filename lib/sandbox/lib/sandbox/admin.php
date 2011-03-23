<?php

// TODO: Document!
class Sandbox_Admin
  implements Prack_I_MiddlewareApp
{
	private $body;
	
	// TODO: Document!
	public function call( $env )
	{
		$user = $env->get( 'REMOTE_USER' );
		
		ob_start();
		  $templates = $env->get( 'sandbox.templates' );
		  include( join( DIRECTORY_SEPARATOR, array( $templates->raw(), 'admin.html.php' ) ) );
		$output = Prb::Str( ob_get_clean() );
		
		return Prb::Ary( array(
		  Prb::Num( 200 ),
		  Prb::Hsh(),
		  Prb::Ary( array( $output ) )
		) );
	}
}