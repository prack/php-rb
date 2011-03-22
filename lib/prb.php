<?php

// TODO: Document!
class Prb
{
	// TODO: Document!
	static function version()
	{
		static $version = null;
		
		if ( is_null( $version ) )
			$version = array( 0, 1, 0 );
		
		return $version;
	}
	
	// TODO: Document!
	static function Ary( $primitive = array() )
	{
		return new Prb_Array( $primitive );
	}
	
	// TODO: Document!
	static function Hsh( $primitive = array() )
	{
		return new Prb_Hash( $primitive );
	}
	
	// TODO: Document!
	static function Set( $primitive = array() )
	{
		return new Prb_Set( $primitive );
	}
	
	// TODO: Document!
	static function Str( $primitive = null )
	{
		return new Prb_String( $primitive );
	}
	
	// TODO: Document!
	static function Num( $primitive = null )
	{
		return new Prb_Numeric( $primitive );
	}
	
	// TODO: Document!
	static function Time( $primitive = null )
	{
		return new Prb_Time( $primitive );
	}
}