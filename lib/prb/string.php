<?php

// TODO: Document!
class Prb_String
  implements Prb_I_Stringable, Prb_I_Comparable
{
	const DELEGATE = 'Prb_DelegateFor_Collection';
	
	private $string;
	
	// TODO: Document!
	static function md5( $string )
	{
		return Prb::Str( md5( $string->raw() ) );
	}
	
	// TODO: Document!
	function __construct( $string = '' )
	{
		// We cast it here in case it's null, which will yield an empty string.
		$this->string = (string)$string;
	}
	
	// TODO: Document!
	function __call( $method, $args )
	{
		if ( method_exists( self::DELEGATE, $method ) )
		{
			array_unshift( $args, $this );
			return call_user_func_array( array( self::DELEGATE, $method ), $args );
		}
		
		throw new Prb_Exception_Runtime_DelegationFailed( "cannot delegate {$method} in Prb_String" );
	}
	
		// TODO: Document!
	public function base64Encode()
	{
		return Prb::Str( base64_encode( $this->string ) );
	}
	
	// TODO: Document!
	public function base64Decode()
	{
		return Prb::Str( base64_decode( $this->string ) );
	}
	
	// TODO: Document!
	public function raw()
	{
		return $this->string;
	}
	
	// TODO: Document!
	public function toS()
	{
		return clone( $this );
	}
	
	// TODO: Document!
	public function toStr()
	{
		return clone( $this );
	}
	
	// TODO: Document!
	public function length()
	{
		return strlen( $this->string );
	}
	
	public function size()  { return $this->length(); }
	
	// TODO: Document!
	public function isEmpty()
	{
		return ( strlen( $this->string ) == 0 );
	}
	
	// TODO: Document!
	public function contains( $substring )
	{
		return ( strpos( $this->string, $substring->raw() ) !== false );
	}
	
	// TODO: Document!
	public function count()
	{
		$args  = func_get_args();
		$found = count_chars( $this->string );
		$sum   = 0;
		
		foreach ( $found as $character => $count )
			$sum += isset( $found[ $character ] ) ? $found[ $character ] : 0;
		
		return array_sum( $found );
	}
	
	// TODO: Document!
	public function squeeze()
	{
		$args    = func_get_args();
		$allowed = $this->parseCountArgs( $args );
		$found   = count_chars( $this->string, 1 );
		$result  = $this->string;
		
		foreach ( $allowed as $character )
		{
			$ord = ord( $character );
			if ( isset( $found[ $ord ] ) && $found[ $ord ] > 1 )
			{
				$quoted = preg_quote( $character, '/' );
				$result = preg_replace( "/{$quoted}{2,}/", $character, $result );
			}
		}
		
		return Prb::Str( $result );
	}
	
	// TODO: Document!
	public function chomp( $separator = null )
	{
		$separator = is_null( $separator ) ? null : $separator->raw();
		return Prb::Str( rtrim( $this->string, $separator ) );
	}
	
	// TODO: Document!
	public function slice()
	{
		$args   = func_get_args();
		$sliced = null;
		
		if ( count( $args ) == 1 )
		{
			$as_array = str_split( $this->string );
			$wrapped  = Prb::Ary();
			
			foreach( $as_array as $item )
				$wrapped->push( Prb::Str( $item ) );
			
			return $wrapped->slice( $args[ 0 ] );
		}
		else if ( count( $args ) == 2 )
		{
			$as_array = str_split( $this->string );
			$wrapped  = Prb::Ary();
			
			foreach( $as_array as $item )
				$wrapped->push( Prb::Str( $item ) );
				
			return $wrapped->slice( $args[ 0 ], $args[ 1 ] )->join( Prb::Str() );
		}
		
		return $sliced;
	}
	
	// TODO: Document!
	public function tr( $from, $to )
	{
		$f_length = $from->length();
		$t_length = $to->length();
		
		if ( $t_length < $f_length )
			$to = $to->rjust( $f_length, $to->slice( -1 ) );
		
		return Prb::Str( strtr( $this->string, $from->raw(), $to->raw() ) );
	}
	
	// TODO: Document!
	public function rjust( $integer, $pad_str = null )
	{
		if ( is_null( $pad_str ) )
			$pad_str = Prb::Str( ' ' );
		
		return Prb::Str( str_pad( $this->string, $integer, $pad_str->raw() ) );
	}
	
	// TODO: Document!
	public function gsub( $pattern, $replacement )
	{
		$string = clone $this;
		$string->gsubInPlace( $pattern, $replacement );
		return $string;
	}
	
	public function gsubInPlace( $pattern, $replacement )
	{
		$result = null;
		if ( is_callable( $replacement ) )
			$result = preg_replace_callback( $pattern, $replacement, $this->string );
		else if ( $replacement instanceof Prb_String )
			$result = preg_replace( $pattern, $replacement->raw(), $this->string );
		
		$this->string = $result;
		
		return $this;
	}
	
	// TODO: Document!
	public function match( $pattern, &$matches = null, $flags = PREG_PATTERN_ORDER )
	{
		return ( preg_match_all( $pattern, $this->string, $matches, $flags ) > 0 );
	}
	
	// TODO: Document!
	public function scan( $pattern )
	{
		$this->match( $pattern, $matches );
		
		if ( empty( $matches ) )
			return Prb::Ary();
		
		$as_array = Prb::Ary();
		
		array_shift( $matches );
		foreach ( $matches as $match )
		{
			if ( is_array( $match ) )
			{
				foreach ( $match as $group_member )
					$as_array->push( Prb::Ary( array( Prb::Str( $group_member ) ) ) );
			}
		}
		
		return $as_array;
	}
	
	// TODO: Document!
	public function concat( $other )
	{
		$this->string .= $other->raw();
		return $this;
	}
	
	// TODO: Document!
	public function split( $pattern = null, $limit = 0 )
	{
		if ( is_null( $pattern ) )
			$pattern = '';
		
		$primitives = preg_split( $pattern, $this->string, $limit );
		$result     = Prb::Ary();
		foreach ( $primitives as $primitive )
			$result->concat( Prb::Str( $primitive ) );
		
		return $result;
	}
	
	// TODO: Document!
	public function sprintf()
	{
		$args = func_get_args();
		$args = Prb::Ary( $args );
		
		static $callback = null;
		if ( is_null( $callback ) )
			$callback = create_function( '$i', 'return is_null( $i ) ? \'\' : $i->toS()->raw();' );
		
		$args = $args->collect( $callback )->raw();
		array_unshift( $args, $this->string );
		
		$result = @call_user_func_array( 'sprintf', $args );
		
		return is_string( $result ) ? Prb::Str( $result ) : null;
	}
	
	// TODO: Document!
	public function parseCountArgs( $args )
	{
		$sets      = array();
		$final_set = array();
		
		foreach ( $args as $arg )
		{
			// Process and remove negation from string.
			$negate_pattern = '/\A\^/';
			$negate         = ( preg_match( $negate_pattern, $arg ) > 0 );
			$arg            = preg_replace( $negate_pattern, '', $arg );
			
			// Process and remove ranges from string.
			$range_pattern  = '/([a-z]-[a-z]|[A-Z]-[A-Z]|[1-9]-[1-9])/';
			$eligible_chars = array();
			for ( $i = preg_match_all( $range_pattern, $arg, $matches ); $i > 0; $i-- )
			{
				$range_components = explode( '-', $matches[ 0 ][ $i - 1 ] );
				$eligible_chars   = array_merge( $eligible_chars, range( $range_components[ 0 ], $range_components[ 1 ] ) );
			}
			$arg = preg_replace( $range_pattern, '', $arg );
			
			// Process the remaining characters.
			if ( !empty( $arg ) )
				$eligible_chars = array_merge( $eligible_chars, str_split( $arg ) );
			
			$final_set = $negate ?  array_diff( $final_set, $eligible_chars )
			                     : array_merge( $final_set, array_diff( $eligible_chars, $final_set ) ); // union.
		}
		
		return $final_set;
	}
	
	// TODO: Document!
	public function upcase()
	{
		return Prb::Str( strtoupper( $this->string ) );
	}
	
	// TODO: Document!
	public function downcase()
	{
		return Prb::Str( strtolower( $this->string ) );
	}
	
	// TODO: Document!
	public function compare( $other_str )
	{
		if ( !( $other_str instanceof Prb_I_Comparable ) && !( method_exists( $other_str, 'toS' ) ) )
			return null;
		
		$other_str = $other_str->toS();
		return strcmp( $this->raw(), $other_str->raw() );
	}
	
	// TODO: Document!
	public function toN()
	{
		return Prb::Num( (float)$this->string );
	}
}