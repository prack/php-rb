<?php

// TODO: Document!
// FIXME: Implement log-shifting.
class Prb_Logger_Device
{
	private $owner;
	private $io;
	
	// FIXME: Use these attributes to implement shifting.
	private $filename;
	private $shift_age;
	private $shift_size;
	
	// TODO: Document!
	// FIXME: Implement log shifting. No-op for now.
	function __construct( $owner, $io_or_filename, $shift_age = null, $shift_size = null )
	{
		$this->owner = $owner;
		
		// Ruby accepts a string for $io_or_filename, opening a logfile if needed.
		// We're going to skip that for simplicity, and assume $io_or_filename
		// is a Prack_Interface_WritableStreamlike.
		// This abstraction will help add more functionality later.
		if ( $io_or_filename instanceof Prb_Interface_WritableStreamlike )
			$this->io = $io_or_filename;
		else
			throw new Prb_Exception_Type( 'FAILSAFE: __construct $io_or_filename must be Prack_Interface_WritableStreamlike' );
			
		$this->filename   = null;
		$this->shift_age  = null;
		$this->shift_size = null;
	}
	
	// TODO: Document!
	public function write( $message )
	{
		// Ruby has mutex stuff here. Since PHP runs in a single process, I'm skipping that.
		try
		{
			$this->io->write( $message );
		}
		catch ( Exception $e )
		{
			$this->owner->warn( Prb::_String( "log writing failed. {$e->getMessage()}" ) );
		}
	}
	
	// TODO: Document!
	public function close()
	{
		try
		{
			$this->io->close();
		}
		catch ( Exception $e ) {}
		
		return null;
	}
	
	// TODO: Document!
	public function getIO()
	{
		return $this->io;
	}
	
	// TODO: Document!
	// public function getFilename()
	// {
	// 	return $this->filename;
	// }
}