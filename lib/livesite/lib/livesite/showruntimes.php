<?php

// TODO: Document!
class Livesite_ShowRuntimes
  implements Prack_I_MiddlewareApp
{
	private $middleware_app;
	private $names;
	
	// TODO: Document!
	public function __construct( $middleware_app, $names = array() )
	{
		$this->middleware_app = $middleware_app;
		$this->names          = $names;
	}
	
	// TODO: Document!
	public function call( &$env )
	{
		list( $status, $headers, $body ) = $this->middleware_app->call( $env );
		
		$response = Prack_Response::with( $body, $status, $headers );
		if ( $response->isOK() && (bool)preg_match( '/text\/html/', $response->get( 'Content-Type' ) )
		     && !(bool)preg_match( '/^\/livesite\/static/', $env[ 'PATH_INFO' ] ) )
		{
			$timers = array();
			foreach( $this->names as $name )
			{
				$full_name = 'X-Runtime-'.$name;
				if ( $time = @$response->get( $full_name ) )
					$timers[ $name ] = $time;
			}
			
			ob_start();
			  include( join( DIRECTORY_SEPARATOR, array( $env[ 'livesite.templates' ], '_runtimes.html.php' ) ) );
			$pretty_runtimes = ob_get_clean();

			// Didn't want to fiddle with XQuery. Cheap, I know.
			$body = preg_replace( '/<div id="floaters">/', '<div id="floaters">'.$pretty_runtimes, $response->getBody() );
		}
		else
			$body = $response->getBody();
		
		return array( $status, $headers, $body );
	}
}
