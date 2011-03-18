<?php

/**
 * @package Prb
 * @abstract
 */
abstract class Prb_Abstract_Collection
{
	const DELEGATE = 'Prb_DelegateFor_Collection';
	
	protected $array;
	
	// TODO: Document!
	function __construct( $array = array() )
	{
		$this->array = $array;
	}
	
	// TODO: Document!
	function __call( $method, $args )
	{
		if ( method_exists( self::DELEGATE, $method ) )
		{
			array_unshift( $args, $this );
			return call_user_func_array( array( self::DELEGATE, $method ), $args );
		}
		
		$this_class = get_class( $this );
		throw new Prb_Exception_Runtime_DelegationFailed( "cannot delegate {$method} in {$this_class}" );
	}
	
	// TODO: Document!
	public function length()
	{
		return sizeof( $this->array );
	}
	
	// TODO: Document!
	public function sortBy( $callback )
	{
		if ( !is_callable( $callback ) )
			throw new Prb_Exception_Callback( 'provided sortBy callback not callable' );
		
		$proxies = $this->map( $callback );
		$keys    = array_keys( $this->array );
		$links   = array();
		
		foreach ( $proxies->raw() as $index => $proxy )
			$links[ spl_object_hash( $proxy ) ] = $keys[ $index ];
		
		$sorted = array();
		foreach ( $proxies->sort()->raw() as $proxy )
		{
			$key            = $links[ spl_object_hash( $proxy ) ];
			$sorted[ $key ] = $this->array[ $key ];
		}
		
		return $sorted;
	}

	public function count() { return $this->length(); }
	public function size()  { return $this->length(); }
	
	// TODO: Document!
	public function isEmpty()
	{
		return ( $this->length() == 0 );
	}
	
	// TODO: Document!
	public function raw()
	{
		return $this->array;
	}
}