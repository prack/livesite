<?php

// TODO: Document!
class Sandbox_Public
  implements Prack_Interface_MiddlewareApp
{
	// TODO: Document!
	public function __construct( $middleware_app )
	{
		$this->middleware_app = $middleware_app;
	}
	
	// TODO: Document!
	public function call( $env )
	{
		$message = Prb::_String( "<h1>Welcome to the Public Site</h1>" );
		
		return Prb::_Array( array(
		  Prb::_Numeric( 200 ),
		  Prb::_Hash(),
		  $message
		) );
	}
}
