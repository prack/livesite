<?php

// TODO: Document!
class Sandbox_Showruntime
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
		list( $status, $headers, $body ) = $this->middleware_app->call( $env )->toA()->raw();
		
		$response = Prack_Response::with( $body, $status, $headers );
		if ( $response->isOK() && !$env->get( 'SCRIPT_NAME' )->match( '/^public/' ) && $response->get( 'Content-Type' )->raw() == 'text/html' )
		{
			$body = $response->getBody()->first();
			$body->gsubInPlace( '/<body>/', Prb::Str(
			  '<body><div id="runtime">Response time: '.$response->get( 'X-Runtime' )->raw().' seconds</div>'
			) );
		}
		
		return $response->toA();
	}
}
