<?php

// TODO: Document!
class Prb_Logger
  implements Prb_Interface_Logger
{
	const DEBUG   = 0;
	const INFO    = 1;
	const WARN    = 2;
	const ERROR   = 3;
	const FATAL   = 4;
	const UNKNOWN = 5;
	
	private $progname;
	private $level;
	private $default_formatter;
	private $formatter;
	private $device;
	
	// TODO: Document!
	static function severityLabel( $severity )
	{
		static $labels = null;
		
		if ( is_null( $labels ) )
		{
			$labels = Prb::_Array( array(
				self::DEBUG   => Prb::_String( 'DEBUG' ),
				self::INFO    => Prb::_String( 'INFO'  ),
				self::WARN    => Prb::_String( 'WARN'  ),
				self::ERROR   => Prb::_String( 'ERROR' ),
				self::FATAL   => Prb::_String( 'FATAL' ),
				self::UNKNOWN => Prb::_String( 'ANY'   )
			) );
		}
		
		return $labels->get( $severity );
	}
	
	// TODO: Document!
	static function with( $device )
	{
		return new Prb_Logger( $device );
	}
	
	// TODO: Document!
	function __construct( $device )
	{
		$this->progname          = null;
		$this->level             = self::DEBUG;
		$this->default_formatter = new Prb_Logger_Formatter();
		$this->formatter         = null;
		$this->device            = null;
		if ( isset( $device ) )
			$this->device = new Prb_Logger_Device( $this, $device );
	}
	
	public function   debug( $progname = null, $callback = null ) { $this->add( self::DEBUG,   null, $progname, $callback ); }
	public function    info( $progname = null, $callback = null ) { $this->add( self::INFO,    null, $progname, $callback ); }
	public function    warn( $progname = null, $callback = null ) { $this->add( self::WARN,    null, $progname, $callback ); }
	public function   error( $progname = null, $callback = null ) { $this->add( self::ERROR,   null, $progname, $callback ); }
	public function   fatal( $progname = null, $callback = null ) { $this->add( self::FATAL,   null, $progname, $callback ); }
	public function unknown( $progname = null, $callback = null ) { $this->add( self::UNKNOWN, null, $progname, $callback ); }
	
	public function logsDebug  () { return ( $this->level <= self::DEBUG   ); }
	public function logsInfo   () { return ( $this->level <= self::INFO    ); }
	public function logsWarn   () { return ( $this->level <= self::WARN    ); }
	public function logsError  () { return ( $this->level <= self::ERROR   ); }
	public function logsFatal  () { return ( $this->level <= self::FATAL   ); }
	public function logsUnknown() { return ( $this->level <= self::UNKNOWN ); }
	
	// TODO: Document!
	public function add( $severity, $message = null, $progname = null, $callback = null )
	{
		$severity = ( $severity !== NULL ) ? $severity : self::UNKNOWN;
		
		if ( is_null( $this->device ) || $severity < $this->level )
			return true;
		
		$progname = is_null( $progname ) ? $this->getProgname() : $progname;
		
		if ( is_null( $message ) )
		{
			if ( is_callable( $callback ) )
				$message = call_user_func( $message );
			else
			{
				$message  = $progname;
				$progname = $this->getProgname();
			}
		}
		
		$this->device->write(
			$this->formatMessage( $this->formatSeverity( $severity ), Prb::_Time(), $this->getProgname(), $message )
		);
		
		return true;
	}
	
	// TODO: Document!
	public function log( $severity, $message = null, $progname = null, $callback = null )
	  { $this->add( $severity, $message, $progname, $callback ); }
	
	// TODO: Document!
	public function concat( $message )
	{
		if ( !( is_null( $this->device ) ) )
			$this->device->write( $message );
	}
	
	// TODO: Document!
	public function close()
	{
		if ( isset( $this->device ) )
			$this->device->close();
	}
	
	// TODO: Document!
	public function getLevel()
	{
		return $this->level;
	}
	
	// TODO: Document!
	public function setLevel( $level )
	{
		$this->level = (int)$level;
	}
	
	// TODO: Document!
	public function getProgname()
	{
		return $this->progname;
	}
	
	// TODO: Document!
	public function setProgname( $progname )
	{
		$this->progname = $progname;
	}
	
	// TODO: Document!
	public function getDatetimeFormat()
	{
		return $this->default_formatter->getDatetimeFormat();
	}
	
	// TODO: Document!
	public function setDatetimeFormat( $datetime_format )
	{
		$this->default_formatter->setDatetimeFormat( $datetime_format );
	}
	
	# Logging formatter.  formatter#call is invoked with 4 arguments; severity,
	# time, progname and msg for each log.  Bear in mind that time is a Time and
	# msg is an Object that user passed and it could not be a String.  It is
	# expected to return a logdev#write-able Object.  Default formatter is used
	# when no formatter is set.

	// TODO: Document!
	public function getFormatter()
	{
		return $this->formatter;
	}
	
	// TODO: Document!
	public function setFormatter( $formatter )
	{
		$this->formatter = $formatter;
	}
	
	// TODO: Document!
	private function formatMessage( $severity, $time, $progname, $message )
	{
		$formatter = is_null( $this->formatter ) ? $this->default_formatter : $this->formatter;
		return $formatter->call( $severity, $time, $progname, $message );
	}
	
	// TODO: Document!
	private function formatSeverity( $severity )
	{
		$sl = self::severityLabel( $severity );
		return is_null( $sl ) ? Prb::_String( 'ANY' ) : $sl;
	}
}