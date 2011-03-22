<?php


class Prb_IOTest extends PHPUnit_Framework_TestCase 
{
	private $stream;
	private $io;
	
	// TODO: Document!
	function setUp()
	{
		$this->stream = fopen( 'php://memory', 'w+b' );
		fwrite( $this->stream, 'hello world' );
		
		$this->io = Prb_IO::withStream( $this->stream, false );
		
		rewind( $this->stream );
	}
	
	// TODO: Document!
	function tearDown()
	{
		@fclose( $this->stream );
	}
	
	/**
	 * It should be able to handle read
	 * @author Joshua Morris
	 * @test
	 */
	public function It_should_be_able_to_handle_read()
	{
		$this->assertEquals( 'hello world', $this->io->read()->raw() );
		
		$this->io->close();
		try
		{
			$this->io->read();
		} 
		catch ( Prb_Exception_IO $e )
		{
			return;
		}
		
		$this->fail( 'Expected exception from read() on read-closed stream.' );
	} // It should be able to handle read
	
	/**
	 * It should be able to handle read( null )
	 * @author Joshua Morris
	 * @test
	 */
	public function It_should_be_able_to_handle_read_with_null()
	{
		$this->assertEquals( 'hello world', $this->io->read( null )->raw() );
	} // It should be able to handle read( null )
	
	/**
	 * It should be able to handle read( length )
	 * @author Joshua Morris
	 * @test
	 */
	public function It_should_be_able_to_handle_read_with_length()
	{
		$this->assertEquals( 'h', $this->io->read( 1 )->raw() );
	} // It should be able to handle read( length )
	
	/**
	 * It should be able to handle read( length, buffer )
	 * @author Joshua Morris
	 * @test
	 */
	public function It_should_be_able_to_handle_read_with_length_and_buffer()
	{
		$buffer = Prb::Str();
		$result = $this->io->read( 1, $buffer );
		$this->assertEquals( 'h', $result->raw() );
		$this->assertSame( $buffer, $result );
	} // It should be able to handle read( length, buffer )
	
	/**
	 * It should be able to handle read( null, buffer )
	 * @author Joshua Morris
	 * @test
	 */
	public function It_should_be_able_to_handle_read_with_null_and_buffer()
	{
		$buffer = Prb::Str();
		$result = $this->io->read( null, $buffer );
		$this->assertEquals( 'hello world', $result->raw() );
		$this->assertSame( $buffer, $result );
	} // It should be able to handle read( null, buffer )
	
	/**
	 * It should rewind to the beginning when rewind is called
	 * @author Joshua Morris
	 * @test
	 */
	public function It_should_rewind_to_the_beginning_when_rewind_is_called()
	{
		$this->io->read( 1 );
		$this->io->rewind();
		$this->assertEquals( 'hello world', $this->io->read()->raw() );
	} // It should rewind to the beginning when rewind is called
	
	/**
	 * It should be able to handle gets
	 * @author Joshua Morris
	 * @test
	 */
	public function It_should_be_able_to_handle_gets()
	{
		$this->assertEquals( 'hello world', $this->io->gets()->raw() );
		
		$this->io->close();
		try
		{
			$this->io->gets();
		} 
		catch ( Prb_Exception_IO $e )
		{
			return;
		}
		
		$this->fail( 'Expected exception from read() on read-closed stream.' );
	} // It should be able to handle gets
	
	/**
	 * It should be able to handle each
	 * @author Joshua Morris
	 * @test
	 */
	public function It_should_be_able_to_handle_each()
	{
		$callback = array( $this, 'addToItems' );
		
		$this->items = Prb::Ary();
		
		$this->io->each( $callback );
		$this->assertEquals( array( Prb::Str( 'hello world' ) ), $this->items->raw() );
		$this->assertEquals( count( $this->items ), $this->io->getLineNo() );
		
		$this->io->close();
		try
		{
			$this->io->each( $callback );
		} 
		catch ( Prb_Exception_IO $e )
		{
			return;
		}
		
		$this->fail( 'Expected exception from each() on read-closed stream.' );
	} // It should be able to handle each
	
	/**
	 * This function is used by the above test as a callback
	 */
	public function addToItems( $item )
	{
		$this->items->concat( $item );
	}
	
	/**
	 * It should throw an exception on each if callback is not callable
	 * @author Joshua Morris
	 * @test
	 */
	public function It_should_throw_an_exception_on_each_if_callback_is_not_callable()
	{
		$callback = array( $this, 'lolnowai' );
		$this->setExpectedException( 'Prb_Exception_Callback' );
		$this->io->each( $callback );
	} // It should throw an exception on each if callback is not callable
	
	/**
	 * It should handle read on really big strings
	 * @author Joshua Morris
	 * @test
	 */
	public function It_should_handle_read_on_really_big_strings()
	{
		$string = Prb_TestHelper::gibberish( Prb_IO::CHUNK_SIZE * 2 );
		$io     = Prb_IO::withString( $string );
		$this->assertEquals( $string, $io->read() );
	} // It should handle read on really big strings
	
	/**
	 * It should handle write
	 * @author Joshua Morris
	 * @test
	 */
	public function It_should_handle_write()
	{
		$this->io->write( Prb::Str( 'EASTER EGG FOR JASON' ) );
		$this->io->rewind();
		
		$this->assertEquals( 'EASTER EGG FOR JASON', $this->io->read()->raw() );
		
		$this->io->close();
		try
		{
			$this->io->write( Prb::Str( 'denied' ) );
		} 
		catch ( Prb_Exception_IO $e )
		{
			return;
		}
		
		$this->fail( 'Expected exception from write() on read-closed stream.' );
	} // It should handle write
	
	/**
	 * It should be possible to call close immediately
	 * @author Joshua Morris
	 * @test
	 */
	public function It_should_be_possible_to_call_close_immediately()
	{
		$this->io->close();
	} // It should be possible to call close immediately
	
	/**
	 * It should be possible to call close multiple times
	 * @author Joshua Morris
	 * @test
	 */
	public function It_should_be_possible_to_call_close_multiple_times()
	{
		$this->io->close();
		$this->io->close();
	} // It should be possible to call close multiple times
}