<?php

// TODO: Document!
class Livesite_ShowEnv
  implements Prack_I_MiddlewareApp
{
	private $middleware_app;
	
	// TODO: Document!
	public function __construct( $middleware_app )
	{
		$this->middleware_app = $middleware_app;
	}
	
	// TODO: Document!
	public function call( &$env )
	{
		list( $status, $headers, $body ) = $this->middleware_app->call( $env );
		
		$response = Prack_Response::with( $body, $status, $headers );
		if ( $response->isOK() )
		{
			ob_start();
			  include( join( DIRECTORY_SEPARATOR, array( $env[ 'livesite.templates' ], '_env.html.php' ) ) );
			$pretty_env = ob_get_clean();
		
			// Didn't want to fiddle with XQuery. Cheap, I know.
			$body = preg_replace( '/<\/h1>/', '</h1>'.$pretty_env, $response->getBody() );
		}
		
		return array( $status, $headers, $body );
	}
}
