<?php

class Prb_Logger_DeviceTest_WeirdIO
  implements Prb_Interface_WritableStreamlike
{
	// TODO: Document!
	public function write( $message )
	{
		throw new Exception( 'derp' );
	}
	
	public function puts()  {}
	public function flush() {}
	// TODO: Document!
	public function close() { throw new Exception( 'I DONT WANT TO BE CLOSED' ); }
}

// TODO: Document!
class Prb_Logger_DeviceTest extends PHPUnit_Framework_TestCase 
{
	/**
	 * It should throw an exception when created with a non-writable-streamlike
	 * @author Joshua Morris
	 * @test
	 */
	public function It_should_throw_an_exception_when_created_with_a_non_writable_streamlike()
	{
		$this->setExpectedException( 'Prb_Exception_Type' );
		new Prb_Logger_Device( null, Prb::_String( 'Not a writable streamlike' ) );
	} // It should throw an exception when created with a non-writable-streamlike
	
	/**
	 * It should notify the owner when the log cannot be written to
	 * @author Joshua Morris
	 * @test
	 */
	public function It_should_notify_the_owner_when_the_log_cannot_be_written_to()
	{
		$mock_owner = $this->getMock( 'LogOwner', array( 'warn' ) );
		$mock_owner->expects( $this->once() )
		           ->method( 'warn' );
		
		$logger_device = new Prb_Logger_Device( $mock_owner, new Prb_Logger_DeviceTest_WeirdIO() );
		$logger_device->write( Prb::_String( 'Trigger the exception' ) );
	} // It should notify the owner when the log cannot be written to
	
	/**
	 * It should write to the underlying io object
	 * @author Joshua Morris
	 * @test
	 */
	public function It_should_write_to_the_underlying_io_object()
	{
		$logger_device = new Prb_Logger_Device( null, Prb_IO::withString(), Prb_Logger::UNKNOWN + 1 );
		$logger_device->write( Prb::_String( 'error lol' ) );
		$logger_device->getIO()->rewind();
		$this->assertTrue( $logger_device->getIO()->read()->match( '/error lol/' ) );
	} // It should write to the underlying io object
	
	/**
	 * It should close the underlying io object
	 * @author Joshua Morris
	 * @test
	 */
	public function It_should_close_the_underlying_io_object()
	{
		// We're only mocking the WeirdIO class because it conforms to writable-streamlike.
		$mock_io = $this->getMock( 'Prb_Logger_DeviceTest_WeirdIO', array( 'close' ) );
		$mock_io->expects( $this->once() )
		        ->method( 'close' );
		
		$logger_device = new Prb_Logger_Device( null, $mock_io );
		$logger_device->close();
	} // It should close the underlying io object
	
	/**
	 * It should ignore exceptions on close
	 * @author Joshua Morris
	 * @test
	 */
	public function It_should_ignore_exceptions_on_close()
	{
		$weird_io      = new Prb_Logger_DeviceTest_WeirdIO();
		$logger_device = new Prb_Logger_Device( null, $weird_io );
		$logger_device->close(); // implicit test.
	} // It should ignore exceptions on close
}