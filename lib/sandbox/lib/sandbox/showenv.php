<?php

// TODO: Document!
class Sandbox_Showenv
  implements Prack_I_MiddlewareApp
{
	private $middleware_app;
	
	// TODO: Document!
	public function __construct( $middleware_app )
	{
		$this->middleware_app = $middleware_app;
	}
	
	// TODO: Document!
	public function call( $env )
	{
		$response = $this->middleware_app->call( $env )->toA();
		
		ob_start();
		  $templates = $env->get( 'sandbox.templates' );
		  include( join( DIRECTORY_SEPARATOR, array( $templates->raw(), '_env.html.php' ) ) );
		$pretty_env = ob_get_clean(); // NOT a wrapped string
		
		// Didn't want to fiddle with XQuery. Cheap, I know.
		$response->get( 2 )->first()->gsubInPlace( '/<\/h1>/', Prb::Str( '</h1>'.$pretty_env ) );
		
		return $response;
	}
}
