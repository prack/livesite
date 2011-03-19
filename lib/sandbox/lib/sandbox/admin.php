<?php

// TODO: Document!
class Sandbox_Admin
  implements Prack_Interface_MiddlewareApp
{
	private $middleware_app;
	private $body;
	
	// TODO: Document!
	public function __construct( $middleware_app )
	{
		$this->middleware_app = $middleware_app;
	}
	
	// TODO: Document!
	public function call( $env )
	{
		$message = Prb::_String( "<h1>Hello, {$env->get( 'REMOTE_USER' )->raw()}!</h1>" );
		return Prb::_Array( array(
		  Prb::_Numeric( 200 ),
		  Prb::_Hash(),
		  $message
		) );
	}
}