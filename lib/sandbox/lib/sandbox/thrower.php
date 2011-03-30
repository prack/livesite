<?php

// TODO: Document!
class Sandbox_Thrower
  implements Prack_I_MiddlewareApp
{
	// TODO: Document!
	public function call( &$env )
	{
		throw new Exception( 'I throw an exception!' );
	}
}
