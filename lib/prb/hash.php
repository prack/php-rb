<?php

/**
 * Class wrapping a PHP as a key/value store object.
 * 
 * This class enforces array key values as strings. When
 * accessing values in the array, all key references are coerced
 * to string values.
 *
 * @package Prb
 */
class Prb_Hash extends Prb_Abstract_Collection
	implements Prb_I_Enumerable
{
	private $default;
	
	// TODO: Document!
	public function get( $key )
	{
		$key = (string)$key;
		if ( is_null( $key ) || !array_key_exists( $key, $this->array ) )
			return $this->getDefault();
		
		return $this->array[ $key ];
	}
	
	// TODO: Document!
	public function fetch()
	{
		$args = func_get_args();
		$argc = count( $args );
		
		switch ( $argc )
		{
			case 1:
				return $this->get( $args[ 0 ] );
			case 2:
				$key = $args[ 0 ];
				if ( is_null( $key ) || !$this->contains( $key ) )
					return $args[ 1 ];
				return $this->get( $key );
		}
		
		return null;
	}
	
	// TODO: Document!
	public function set( $key, $item )
	{
		$this->array[ $key ] = $item;
	}
	
	// TODO: Document!
	public function delete( $key )
	{
		$result = isset( $this->array[ $key ] ) ? $this->array[ $key ]
		                                        : $this->getDefault();
		unset( $this->array[ $key ] );
		return $result;
	}
	
		// TODO: Document!
	public function clear()
	{
		$this->array = array();
		return $this;
	}
	
	// TODO: Document!
	public function merge( $other )
	{
		$hash = clone $this;
		$hash->mergeInPlace( $other );
		return $hash;
	}
	
	// TODO: Document!
	public function mergeInPlace( $other )
	{
		foreach ( $other->raw() as $key => $value )
			$this->set( $key, $value );
		return $this;
	}
	
	public function update( $other ) { return $this->mergeInPlace( $other ); }
	
	// TODO: Document!
	public function each( $callback )
	{
		if ( !is_callable( $callback ) )
			throw new Prb_Exception_Callback();
		
		// I disagree with array_walk function.
		foreach ( $this->array as $key => $value )
			call_user_func( $callback, $key, $value );
	}
	
	// TODO: Document!
	public function inject( $accumulator, $callback )
	{
		if ( !is_callable( $callback ) )
			throw new Prb_Exception_Callback();
		
		$result = $accumulator;
		
		foreach ( $this->array as $key => $value )
		{
			if ( $value instanceof Prb_Array )
			{
				$args = $value->raw();
				array_unshift( $args, $result );
				$result = call_user_func_array( $callback, $args );
			}
			else
				$result = call_user_func( $callback, $result, $key, $value );
		}
		
		return $result;
	}

	// TODO: Document!
	public function collect( $callback )
	{
		if ( !is_callable( $callback ) )
			throw new Prb_Exception_Callback();
		
		$map = array();
		
		// I disagree with array_map function.
		foreach ( $this->array as $key => $item )
			array_push( $map, call_user_func( $callback, $key, $item ) );
		
		return Prb::Ary( $map );
	}
	
	public function map( $callback ) { return $this->collect( $callback ); }
	
	// TODO: Document!
	public function contains( $key )
	{
		return is_null( $key ) ? false : array_key_exists( $key, $this->array );
	}
	
	// TODO: Document!
	public function hasKey  ( $key ) { return $this->contains( $key ); }
	public function isMember( $key ) { return $this->contains( $key ); }
	
	// TODO: Document!
	public function slice()
	{
		$args = func_get_args();
		return call_user_func_array( array( $this, 'valuesAt' ), $args );
	}
	
	// TODO: Document!
	public function getDefault()
	{
		return is_null( $this->default ) ? null : clone $this->default;
	}
	
	// TODO: Document!
	public function setDefault( $default = null )
	{
		$this->default = $default;
	}
	
	public function sortBy( $callback )
	{
		return Prb::Hsh( parent::sortBy( $callback ) );
	}
	
	// TODO: Document!
	public function toHash()
	{
		return clone $this;
	}
	
	// TODO: Document!
	public function toA()
	{
		$array = Prb::Ary();
		foreach ( $this->array as $key => $item )
			$array->push( Prb::Ary( array( $key, $item ) ) );
		return $array;
	}
}