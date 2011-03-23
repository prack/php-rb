<?php

class Prb_DelegateFor_Collection
{
	/**
	 * Translates a negative index to a positive one.
	 * 
	 * Negative indexes, starting with -1, refer to the last object
	 * in an array. However, unlike positive indexes, out-of-range
	 * negative indexes cannot be used. For example, if an array
	 * contains 3 items, an index of -1 would translate to 2 (the
	 * largest index in the collection), and an index of -5 will
	 * result in null.
	 *
	 * A positive index of 6, on the other hand, is translated to 6,
	 * since out-of-bound positive indexes are allowed for certain
	 * operations.
	 *
	 * @static
	 * @param mixed $wrapper the object requesting this method
	 * @param integer $index index to be translated
	 * @return mixed integer if successfully translated; otherwise, null
	 */
	static function translate( $wrapper, $index )
	{
		$translated = ( $index < 0 ) ? $wrapper->length() + (int)$index
		                             : (int)$index;
		
		return ( $translated >= 0 ) ? $translated : null;
	}
	
	/**
	 * Translates a negative index into a positive one, raising
	 * an exception if the translation results in null.
	 * 
	 * @static
	 * @param mixed $wrapper the object requesting this method
	 * @param integer $index index to be translated
	 * @return integer
	 * @throws Prb_Exception_Index
	 */
	static function translateBang( $wrapper, $index )
	{
		$translated = Prb_DelegateFor_Collection::translate( $wrapper, $index );
		
		if ( is_null( $translated ) )
		{
			$class  = get_class( $wrapper );
			$lowest = -$wrapper->length();
			throw new Prb_Exception_Index( "index too small for {$class}; minimum {$lowest}" );
		}
		
		return $translated;
	}
	
	// TODO: Document!
	static function valuesAt()
	{
		$args    = func_get_args();
		$wrapper = array_shift( $args );
		$wrapped = Prb::Ary();
		
		foreach ( $args as $key )
			$wrapped->push( $wrapper->get( $key ) );
		
		return $wrapped;
	}
	
	// TODO: Document!
	static function keys( $wrapper )
	{
		$keys = array_keys( $wrapper->raw() );
		
		$wrapped = Prb::Ary();
		foreach ( $keys as $key )
			$wrapped->push( Prb::Str( $key ) );
		
		return $wrapped;
	}
	
	// TODO: Document!
	static function detect( $wrapper, $callback )
	{
		$raw = $wrapper->raw();
		foreach ( $raw as $index => $item )
		{
			if ( is_numeric( $index ) )
				$result = call_user_func( $callback, $item );
			else if ( is_string( $index ) )
				$result = call_user_func( $callback, $index, $item );
			
			if ( $result == true )
				return $item;
		}
		
		return null;
	}
	
	// TODO: Document!
	static function detectAny( $wrapper, $callback )
	{
		$detected = Prb_DelegateFor_Collection::detect( $wrapper, $callback );
		return isset( $detected );
	}
}