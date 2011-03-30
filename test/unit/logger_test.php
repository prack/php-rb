<?php

// TODO: Document!
class Prack_Utils_LoggerTest extends PHPUnit_Framework_TestCase 
{
	/**
	 * It should know its level
	 * @author Joshua Morris
	 * @test
	 */
	public function It_should_know_its_level()
	{
		$logger = Prb_Logger::with( Prb_IO::withString() );
		$this->assertEquals( Prb_Logger::DEBUG, $logger->getLevel() );
		
		$this->assertTrue( $logger->logsDebug()   );
		$this->assertTrue( $logger->logsInfo()    );
		$this->assertTrue( $logger->logsWarn()    );
		$this->assertTrue( $logger->logsError()   );
		$this->assertTrue( $logger->logsFatal()   );
		$this->assertTrue( $logger->logsUnknown() );
		
		$logger->setLevel( Prb_Logger::INFO );
		$this->assertFalse( $logger->logsDebug()   );
		$this->assertTrue ( $logger->logsInfo()    );
		$this->assertTrue ( $logger->logsWarn()    );
		$this->assertTrue ( $logger->logsError()   );
		$this->assertTrue ( $logger->logsFatal()   );
		$this->assertTrue ( $logger->logsUnknown() );
		
		$logger->setLevel( Prb_Logger::WARN );
		$this->assertFalse( $logger->logsDebug()   );
		$this->assertFalse( $logger->logsInfo()    );
		$this->assertTrue ( $logger->logsWarn()    );
		$this->assertTrue ( $logger->logsError()   );
		$this->assertTrue ( $logger->logsFatal()   );
		$this->assertTrue ( $logger->logsUnknown() );
		
		$logger->setLevel( Prb_Logger::ERROR );
		$this->assertFalse( $logger->logsDebug()   );
		$this->assertFalse( $logger->logsInfo()    );
		$this->assertFalse( $logger->logsWarn()    );
		$this->assertTrue ( $logger->logsError()   );
		$this->assertTrue ( $logger->logsFatal()   );
		$this->assertTrue ( $logger->logsUnknown() );
		
		$logger->setLevel( Prb_Logger::FATAL );
		$this->assertFalse( $logger->logsDebug()   );
		$this->assertFalse( $logger->logsInfo()    );
		$this->assertFalse( $logger->logsWarn()    );
		$this->assertFalse( $logger->logsError()   );
		$this->assertTrue ( $logger->logsFatal()   );
		$this->assertTrue ( $logger->logsUnknown() );
		
		$logger->setLevel( Prb_Logger::UNKNOWN );
		$this->assertFalse( $logger->logsDebug()   );
		$this->assertFalse( $logger->logsInfo()    );
		$this->assertFalse( $logger->logsWarn()    );
		$this->assertFalse( $logger->logsError()   );
		$this->assertFalse( $logger->logsFatal()   );
		$this->assertTrue ( $logger->logsUnknown() );
	} // It should know its level
	
	/**
	 * It should log messages of appropriate severity
	 * @author Joshua Morris
	 * @test
	 */
	public function It_should_log_messages_of_appropriate_severity()
	{
		$io     = Prb_IO::withString();
		$logger = Prb_Logger::with( $io );
		
		$logger->warn( 'error lol' );
		$io->rewind();
		$this->assertRegExp( '/error lol/', $io->read() );
	} // It should log messages of appropriate severity
	
	/**
	 * It should not log messages of inappropriate severity
	 * @author Joshua Morris
	 * @test
	 */
	public function It_should_not_log_messages_of_inappropriate_severity()
	{
		$io     = Prb_IO::withString();
		$logger = Prb_Logger::with( $io );
		$logger->setLevel( Prb_Logger::UNKNOWN + 1 );
		$error  = 'error lol';
		
		$logger->debug( $error );
		$io->rewind();
		$this->assertNotRegExp( '/error lol/', $io->read() );
		
		$logger->info( $error );
		$io->rewind();
		$this->assertNotRegExp( '/error lol/', $io->read() );
		
		$logger->warn( $error );
		$io->rewind();
		$this->assertNotRegExp( '/error lol/', $io->read() );
		
		$logger->error( $error );
		$io->rewind();
		$this->assertNotRegExp( '/error lol/', $io->read() );
		
		$logger->fatal( $error );
		$io->rewind();
		$this->assertNotRegExp( '/error lol/', $io->read() );
		
		$logger->unknown( $error );
		$io->rewind();
		$this->assertNotRegExp( '/error lol/', $io->read() );
	} // It should not log messages of inappropriate severity
	
	/**
	 * It should alias method add to log
	 * @author Joshua Morris
	 * @test
	 */
	public function It_should_alias_method_add_to_log()
	{
		$io     = Prb_IO::withString();
		$logger = Prb_Logger::with( $io );
		
		$logger->log( Prb_Logger::WARN, 'error lol' );
		$io->rewind();
		
		$this->assertRegExp( '/error lol/', $io->read() );
	} // It should alias method add to log
	
	/**
	 * It should allow direct concatenation to the device
	 * @author Joshua Morris
	 * @test
	 */
	public function It_should_allow_direct_concatenation_to_the_device()
	{
		$io     = Prb_IO::withString();
		$logger = Prb_Logger::with( $io );
		
		$logger->concat( 'error lol' );
		$io->rewind();
		
		$this->assertRegExp( '/error lol/', $io->read() );
	} // It should allow direct concatenation to the device
	
	/**
	 * It should allow direct setting of the default formatter's datetime format
	 * @author Joshua Morris
	 * @test
	 */
	public function It_should_allow_direct_setting_of_the_default_formatter_s_datetime_format()
	{
		$io     = Prb_IO::withString();
		$dtf    = '%H';
		$logger = Prb_Logger::with( $io );
		
		$logger->setDatetimeFormat( $dtf );
		$this->assertSame( $logger->getDatetimeFormat(), $dtf );
		
		$logger->info( 'hello' );
		$io->rewind();
		$this->assertRegExp( '/\[\d\d#\d{1,5}\].*hello/', $io->read() );
	} // It should allow direct setting of the default formatter's datetime format
	
	/**
	 * It should allow setting of the formatter to use for logging
	 * @author Joshua Morris
	 * @test
	 */
	public function It_should_allow_setting_of_the_formatter_to_use_for_logging()
	{
		$mock_formatter = $this->getMock( 'Formatter', array( 'call' ) );
		$mock_formatter->expects( $this->once() )
		               ->method( 'call' )
		               ->will( $this->returnValue( 'io' ) );
		
		$logger = Prb_Logger::with( Prb_IO::withString() );
		$logger->setFormatter( $mock_formatter );
		$logger->log( Prb_Logger::INFO, 'hello world' );
		$this->assertSame( $mock_formatter, $logger->getFormatter() );
	} // It should allow setting of the formatter to use for logging
}