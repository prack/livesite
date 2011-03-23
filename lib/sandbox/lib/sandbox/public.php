<?php

// TODO: Document!
class Sandbox_Public
  implements Prack_I_MiddlewareApp
{
	// TODO: Document!
	public function call( $env )
	{
		$user = $env->get( 'REMOTE_USER' );
		
		ob_start();
		  $templates = $env->get( 'sandbox.templates' );
		  include( join( DIRECTORY_SEPARATOR, array( $templates->raw(), 'public.html.php' ) ) );
		$output = Prb::Str( ob_get_clean() );
		
		return Prb::Ary( array(
		  Prb::Num( 200 ),
		  Prb::Hsh(),
		  Prb::Ary( array( $output ) )
		) );
	}
}
