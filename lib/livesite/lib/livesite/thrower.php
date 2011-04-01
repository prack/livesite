<?php

// TODO: Document!
class Livesite_Thrower
  implements Prack_I_MiddlewareApp
{
	// TODO: Document!
	public function call( &$env )
	{
		throw new Exception( 'I throw an exception!' );
	}
}
