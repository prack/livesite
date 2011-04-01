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
			$runtime_elements = array();
			foreach( $this->names as $name )
			{
				$full_name = 'X-Runtime-'.$name;
				if ( @$response->get( $full_name ) )
					array_push( $runtime_elements, "<li class=\"runtime\" id=\"".$name."\">'{$name}' time: ".$response->get( $full_name )." seconds</li>" );
			}
			
			// Didn't want to fiddle with XQuery. Cheap, I know.
			$body = preg_replace(
			  '/<body>/',
			  '<body><ol id="runtimes">'.join( '', $runtime_elements ).'</ol>',
			  $response->getBody()
			);
		}
		else
			$body = $response->getBody();
		
		return array( $status, $headers, $body );
	}
}
