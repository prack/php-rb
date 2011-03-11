<?php

// TODO: Document!
class Prb_Set extends Prb_Array
  implements Prb_Interface_Enumerable
{
	// TODO: Document!
	public function each( $callback )
	{
		if ( !is_callable( $callback ) )
			throw new Prb_Exception_Callback();
		
		foreach ( $this->array as $key => $item )
			call_user_func( $callback, $item );
	}
	
	// TODO: Document!
	public function collect( $callback )
	{
		if ( !is_callable( $callback ) )
			throw new Prb_Exception_Callback();
		
		foreach ( $this->array as $key => $item )
			call_user_func( $callback, $key, $item );
	}
}
