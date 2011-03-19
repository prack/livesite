<?php

// TODO: Document!
class Sandbox_Showenv
  implements Prack_Interface_MiddlewareApp
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
		  var_dump( $env );
		$env_dump = Prb::_String( ob_get_clean() );
		
		$response->get( 2 )->concat( $env_dump );
		
		return $response;
	}
}
