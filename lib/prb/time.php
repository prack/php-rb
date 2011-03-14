<?php

// TODO: Document!
class Prb_Time extends Prb_Numeric
{
	private $seconds;
	private $microseconds;
	
	// TODO: Document!
	function __construct( $time = null )
	{
		if ( is_null( $time ) )
			$time = microtime( true );
		
		parent::__construct( $time );
		
		$abs = abs( $time );
		$this->seconds      = Prb::_Numeric( (int)$abs );
		$this->microseconds = Prb::_Numeric( (int)( ( $abs - floor( $abs ) ) * pow( 10, 6 ) ) );
	}
	
	// TODO: Document!
	public function strftime( $format )
	{
		if ( !( $format instanceof Prb_String ) )
			throw new Prb_Exception_Argument( 'strftime $format must be instance of Prb_String' );
		
		$formatted = strftime( $format->raw(), (int)$this->numeric );
		return is_string( $formatted ) ? Prb::_String( $formatted ) : Prb::_String();
	}
	
	// TODO: Document!
	public function getSeconds()
	{
		return $this->seconds;
	}
	
	// TODO: Document!
	public function getMicroseconds()
	{
		return $this->microseconds;
	}
}
