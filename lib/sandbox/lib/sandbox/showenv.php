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
		list( $status, $headers, $body ) = $this->middleware_app->call( $env )->raw();
		
		$response = Prack_Response::with( $body, $status, $headers );
		ob_start();
		  $templates = $env->get( 'sandbox.templates' );
		  include( join( DIRECTORY_SEPARATOR, array( $templates->raw(), '_env.html.php' ) ) );
		$pretty_env = ob_get_clean(); // NOT a wrapped string
		
		// Didn't want to fiddle with XQuery. Cheap, I know.
		$response->getBody()->first()->gsubInPlace( '/<\/h1>/', Prb::Str( '</h1>'.$pretty_env ) );
		
		return $response->toA();
	}
}
