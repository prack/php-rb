<?php

// TODO: Document!
class Prb_Logger_Formatter
{
	private $datetime_format;
	
	// TODO: Document!
	static function format()
	{
		static $format = null;
		
		if ( is_null( $format ) )
			$format = Prb::Str( "%s, [%s#%d] %5s -- %s: %s\n" );
		
		return $format;
	}
	
	// TODO: Document!
	function __construct()
	{
		$this->datetime_format = null;
	}
	
	// TODO: Document!
	public function call( $severity, $time, $progname, $message )
	{
		return self::format()->sprintf(
	    $severity->slice( 0, 0 ),
	    $this->formatDatetime( $time ),
	    Prb::Num( getmypid() ),
	    $severity,
	    $progname,
	    $this->msg2str( $message )
		);
	}
	
	// TODO: Document!
	public function getDatetimeFormat()
	{
		return $this->datetime_format;
	}
	
	// TODO: Document!
	public function setDatetimeFormat( $datetime_format )
	{
		if ( !( $datetime_format instanceof Prb_String ) )
			throw new Prb_Exception_Type( 'setDatetimeFormat $datetime_format must be instance of Prb_String' );
		
		$this->datetime_format = $datetime_format;
	}
	
	// TODO: Document!
	private function formatDatetime( $time )
	{
		if ( is_null( $this->getDatetimeFormat() ) )
			return $time->strftime( Prb::Str( '%Y-%m-%dT%H:%M:%S.' ) )
		              ->concat( Prb::Str( '%06d' )->sprintf( $time->getMicroseconds() ) );
		else
			return $time->strftime( $this->getDatetimeFormat() );
	}
	
	// TODO: Document!
	private function msg2str( $message )
	{
		if ( $message instanceof Prb_String )
			return $message;
		else if ( $message instanceof Exception )
		{
			$exception_class = get_class( $message );
			return Prb::Str( "{$message->getMessage()} ($exception_class)" )
			           ->concat( Prb::Str( $message->getTraceAsString() ) );
		}
		return Prb::Str( print_r( $message, true ) );
	}
}