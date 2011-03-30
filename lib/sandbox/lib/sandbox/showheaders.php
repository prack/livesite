<?php

// TODO: Document!
class Sandbox_Showheaders
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
		if ( $response->isOK() && (bool)preg_match( '/text\/html/', $response->get( 'Content-Type' ) ) )
		{
			ob_start();
			  include( join( DIRECTORY_SEPARATOR, array( $env[ 'sandbox.templates' ], '_headers.html.php' ) ) );
			$pretty_env = ob_get_clean();
			
			// Didn't want to fiddle with XQuery. Cheap, I know.
			$response = Prack_Response::with(
				preg_replace( '/<body>/', '<body>'.$pretty_env, $body ),
				$response->getStatus(),
				$response->getHeaders()->raw()
			);
			
			list( $status, $headers, $body ) = $response->finish();
		}
		
		return array( $status, $headers, $body );
	}
}
