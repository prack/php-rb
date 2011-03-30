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
			$format = "%s, [%s#%d] %5s -- %s: %s\n";
		
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
		return sprintf( self::format(),
		  substr( $severity, 0, 1 ),
		  $this->formatDatetime( $time ),
		  getmypid(),
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
		if ( !is_string( $datetime_format ) )
			throw new Prb_Exception_Type( 'setDatetimeFormat $datetime_format must be a string' );
		
		$this->datetime_format = $datetime_format;
	}
	
	// TODO: Document!
	private function formatDatetime( $time )
	{
		if ( is_null( $this->getDatetimeFormat() ) )
			return $time->strftime( '%Y-%m-%dT%H:%M:%S.' ).sprintf( '%06d', $time->getMicroseconds() );
		else
			return $time->strftime( $this->getDatetimeFormat() );
	}
	
	// TODO: Document!
	private function msg2str( $message )
	{
		if ( is_string( $message ) )
			return $message;
		else if ( $message instanceof Exception )
		{
			$exception_class = get_class( $message );
			return "{$message->getMessage()} ($exception_class)".$message->getTraceAsString();
		}
		return print_r( $message, true );
	}
}