<?php

// TODO: Document!
class Livesite_ShowEnv
  implements Prack_I_MiddlewareApp
{
	private $middleware_app;
	
	// TODO: Document!
	static function allowInProduction()
	{
		$allow_in_production = null;
		
		if ( is_null( $allow_in_production ) )
			$allow_in_production =
			  array(
			    '/HTTP_*/', '/rack\..+/', '/livesite\..+/', '/SCRIPT_NAME/', '/PATH_INFO/', '/QUERY_STRING/'
			  );
		
		return $allow_in_production;
	}
	
	// TODO: Document!
	public function __construct( $middleware_app )
	{
		$this->middleware_app = $middleware_app;
	}
	
	// TODO: Document!
	public function call( &$env )
	{
		list( $status, $headers, $body ) = $this->middleware_app->call( $env );
		
		$display_env = $this->displayEnv( $env );
		$response    = Prack_Response::with( $body, $status, $headers );
		$headers     = Prack_Utils_HeaderHash::using( $headers );
		if ( $response->isOK() )
		{
			$headers->delete( 'Content-Length' );
			
			ob_start();
			  include( join( DIRECTORY_SEPARATOR, array( $env[ 'livesite.templates' ], '_env.html.php' ) ) );
			$pretty_env = ob_get_clean();
		
			// Didn't want to fiddle with XQuery. Cheap, I know.
			$body = preg_replace( '/<div id="diagnostics">/', '<div id="diagnostics">'.$pretty_env, $response->getBody() );
		}
		
		return array( $status, $headers->raw(), $body );
	}
	
	// TODO: Document!
	public function displayEnv( $env )
	{
		if ( strcmp( strtolower( (string)@getenv( 'RACK_ENV' ) ), 'production' ) == 0 )
		{
			foreach ( $env as $key => $value )
			{
				$found = false;
				foreach ( self::allowInProduction() as $regex )
				{
					$found = (bool)preg_match( $regex, $key );
					if ( $found === true )
						break;
				}
				if ( !$found )
					unset( $env[ $key ] );
			}
		}
		
		return $env;
	}
}
