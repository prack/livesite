<?php

// TODO: Document!
class Sandbox_Trollface
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
		
		if ( !@$env[ 'REMOTE_USER' ] && $status == 401 )
		{
			ob_start();
			  include( join( DIRECTORY_SEPARATOR, array( $env[ 'sandbox.templates' ], 'trollface.html.php' ) ) );
			$trollface = ob_get_clean();
			
			$response = Prack_Response::with( $trollface, 200, $headers );
			$response->set( 'X-Trolled', 'definitely' );
			$response->set( 'Content-Type', 'text/html' );
			
			return $response->finish();
		}
		
		return array( $status, $headers, $body );
	}
}
