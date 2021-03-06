<?php

/**
 * Class wrapping a PHP array as indexed-only array object.
 * 
 * This class enforces array key values as integers. When
 * accessing values in the array, all key references are coerced
 * to integer values. Also, unlike traditional PHP arrays, index
 * values are continuous: if a value is inserted at an index greater
 * than the current capacity, intermediate keys will be created and
 * their respective values will be set to null.
 *
 * @package Prb
 */
class Prb_Array extends Prb_Abstract_Collection
  implements Prb_I_Enumerable, Prb_I_Comparable, Prb_I_Arraylike
{
	// TODO: Document!
	public function get( $key )
	{
		$key = $this->translate( (int)$key );
		
		if ( is_null( $key ) || !array_key_exists( $key, $this->array ) )
			return null;
		
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
				return $this->get( $this->translateBang( $args[ 0 ] ) );
			case 2:
				$key = $this->translate( $args[ 0 ] );
				if ( is_null( $key ) || !array_key_exists( $key, $this->array ) )
					return $args[ 1 ];
				return $this->get( $key );
		}
		
		return null;
	}
	
	// TODO: Document!
	public function set( $index, $item )
	{
		$index  = $this->translateBang( (int)$index );
		$length = $this->length();
		
		if ( $index > $length )
			for ( $i = 0; $i < ( $index - $length ); $i++ )
				$this->array[ $length + $i ] = null;
		
		$this->array[ $index ] = $item;
	}
	
	// TODO: Document!
	public function first()
	{
		return $this->get( 0 );
	}
	
	// TODO: Document!
	public function last()
	{
		return $this->get( -1 );
	}
	
	// TODO: Document!
	public function concat()
	{
		$args = func_get_args();
		
		foreach( $args as $arg )
			array_push( $this->array, $arg );
		
		return $this;
	}
	
	// TODO: Document!
	public function each( $callback )
	{
		if ( !is_callable( $callback ) )
			throw new Prb_Exception_Callback();
		
		foreach ( $this->array as $item )
		{
			if ( $item instanceof Prb_Array )
				call_user_func_array( $callback, $item->raw() );
			else
				call_user_func( $callback, $item );
		}
	}
	
	// TODO: Document!
	public function eachIndex( $callback )
	{
		if ( !is_callable( $callback ) )
			throw new Prb_Exception_Callback();
		
		foreach( $this->array as $index => $item )
			call_user_func( $callback, $index );
	}
	
	// TODO: Document!
	public function inject( $accumulator, $callback )
	{
		if ( !is_callable( $callback ) )
			throw new Prb_Exception_Callback();
		
		$result = $accumulator;
		
		foreach ( $this->array as $item )
		{
			if ( $item instanceof Prb_Array )
			{
				$args = $item->raw();
				array_unshift( $args, $result );
				$result = call_user_func_array( $callback, $args );
			}
			else
				$result = call_user_func( $callback, $result, $item );
		}
		
		return $result;
	}
	
	// TODO: Document!
	public function select( $callback )
	{
		if ( !is_callable( $callback ) )
			throw new Prb_Exception_Callback();
		
		$selected = array();
		
		foreach ( $this->array as $item )
		{
			if ( $item instanceof Prb_Array )
				$valid = call_user_func_array( $callback, $item->raw() );
			else
				$valid = call_user_func( $callback, $item );
			
			if ( $valid == true )
				array_push( $selected, $item );
		}
		
		return Prb::Ary( $selected );
	}
	
		// TODO: Document!
	public function sortBy( $callback )
	{
		return Prb::Ary( parent::sortBy( $callback ) );
	}
	
	// TODO: Document!
	public function collect( $callback )
	{
		if ( !is_callable( $callback ) )
			throw new Prb_Exception_Callback( 'provided collect callback not callable' );
		
		$map = array();
		
		foreach ( $this->array as $item )
		{
			if ( $item instanceof Prb_Array )
				array_push( $map, call_user_func_array( $callback, $item->raw() ) );
			else
				array_push( $map, call_user_func( $callback, $item ) );
		}
		
		return Prb::Ary( $map );
	}
	
	public function map( $callback ) { return $this->collect( $callback ); }

	// TODO: Document!
	public function sort( $callback = null )
	{
		static $default_callback = null;
		
		if ( is_null( $default_callback ) )
			$default_callback = create_function( '$l,$r', 'return $l->compare( $r );' );
		
		$callback = isset( $callback ) ?  $callback : $default_callback;
		if ( !is_callable( $callback ) )
			throw new Prb_Exception_Callback( 'provided sort callback not callable' );
		
		$sorted = $this->array;
		usort( $sorted, $callback );
		
		return Prb::Ary( $sorted );
	}
	
	// TODO: Document!
	public function slice()
	{
		$args = func_get_args();
		
		if ( count( $args ) == 1 )
			$result = $this->get( $args[ 0 ] );
		else if ( count( $args ) == 2 || count( $args ) == 3 && is_bool( $args[ 2 ] ) )
		{
			$translated_start = $this->translate( $args[ 0 ] );
			$translated_end   = $this->translate( $args[ 1 ] );
			$exclusive        = isset( $args[ 2 ] ) ? $args[ 2 ] : false;
			if ( is_null( $translated_start ) || is_null( $translated_end ) )
				return null;
			
			if ( $exclusive === true )
			{
				$translated_end--;
				if ( $translated_end < 0 )
					return Prb::Ary();
			}
			
			if ( $translated_end >= $this->length() )
				$translated_end = $this->length() - 1;
			
			$result = call_user_func_array( array( $this, 'valuesAt' ),
			                                range( $translated_start, $translated_end ) );
		}
		
		return isset( $result ) ? $result : null;
	}
	
	// TODO: Document!
	public function compact()
	{
		$compacted = Prb::Ary();
		foreach ( $this->raw() as $item )
			if ( isset( $item ) )
				$compacted->push( $item );
		
		return $compacted;
	}
	
	// TODO: Document!
	public function push( $item )
	{
		array_push( $this->array, $item );
	}
	
	// TODO: Document!
	public function pop()
	{
		return array_pop( $this->array );
	}
	
	// TODO: Document!
	public function unshift( $item )
	{
		array_unshift( $this->array, $item );
	}
	
	// TODO: Document!
	public function shift()
	{
		return array_shift( $this->array );
	}
	
	// TODO: Document!
	public function join( $separator = null )
	{
		if ( is_null( $separator ) )
			$separator = Prb::Str( ' ' );
		
		$as_strings = Prb::Ary();
		foreach ( $this->array as $item )
			$as_strings->push( is_null( $item ) ? '' : $item->toS()->raw() );
		
		return Prb::Str( implode( $separator->raw(), $as_strings->raw() ) );
	}
	
	// TODO: Document!
	public function contains( $item )
	{
		return in_array( $item, $this->array );
	}
	
	// TODO: Document!
	public function toA()
	{
		return $this;
	}
	
	// TODO: Document!
	public function toAry() { return $this->toA(); }
	
	/**
	 * Object-level comparison of this instance to another.
	 * 
	 * Compare this instance to another comparable instance, which must
	 * respond to toA(), lest this function return null (meaning 'incomparable' ).
	 * 
	 * Returns a negative integer, zero, or a positive integer if this instance
	 * is less than, equal to, or greater than $other_ary, respectively. Each
	 * object in each array is compared using the item's compare function. If 
	 * the result isn't equal, then that inequality is returned by this function.
	 * 
	 * If all the values found are equal, then the return is based on comparison
	 * of the array lengths. Thus, two arrays are 'equal' according to compare
	 * if and only if they have the same length and the value of each element is
	 * equal to the value of the corresponding element in the other array.
	 *
	 * Note: This function doesn't require Prb-included objects, but each object
	 * MUST conform to Prb_I_Comparable. Primitives like php strings,
	 * arrays, and numerics anywhere in the array will result in being
	 * incomparable. This is by design, since PHP type comparison is a world
	 * unto itself.
	 *
	 * @author Joshua Morris
	 * @access public
	 * @param $other_ary mixed Prb_I_Comparable responding to toA
	 * @return mixed see function description
	 */
	// This function is an example of PHP's ad-hoc inferiority.
	public function compare( $other_ary )
	{
		if ( !( $other_ary instanceof Prb_Arraylike ) )
			return null;
		
		$comparison = 0; // equal
		$this_ary   = clone $this;
		$other_ary  = $other_ary->toA();
		
		// We may need to expand this array.
		if ( $other_ary->length() > $this_ary->length() )
			$this_ary->set( $other_ary->length() - 1, null );
		
		foreach( $this_ary->raw() as $index => $item )
		{
			$other_item = $other_ary->get( $index );
			
			// Two nulls are 'equal'.
			if ( is_null( $item ) && is_null( $other_item ) )
				continue;
			
			// Compare two objects via compare method:
			else if ( $item       instanceof Prb_I_Comparable &&
				        $other_item instanceof Prb_I_Comparable    )
			{
				$comparison = $item->compare( $other_item );
				if ( $comparison != 0 )
					return $comparison;
			}
			
			// Everything else returns null.
			else
				return null;
		}
		
		return $this->length() - $other_ary->length();
	}
	
	// TODO: Document!
	public function reverse()
	{
		return Prb::Ary( array_reverse( $this->array ) );
	}
}