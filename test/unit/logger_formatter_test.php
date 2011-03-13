<?php

// TODO: Document!
class Prb_Logger_FormatterTest extends PHPUnit_Framework_TestCase 
{
	/**
	 * It should format a log entry correctly by default
	 * @author Joshua Morris
	 * @test
	 */
	public function It_should_format_a_log_entry_correctly_by_default()
	{
		$formatter = new Prb_Logger_Formatter();
		
		$this->assertTrue(
		  $formatter->call(
		    Prb_Logger::severityLabel( Prb_Logger::WARN ),
		    Prb::_Time(),
		    Prb::_String( 'someprogram' ),
		    array( 'primitive' )
		  )->match( "/W, \[\d{4}-\d\d-\d\dT\d\d:\d\d:\d\d\.\d{6}#\d+\]  WARN -- someprogram/" )
		);
	} // It should format a log entry correctly by default
	
	/**
	 * It should handle custom datetime formats
	 * @author Joshua Morris
	 * @test
	 */
	public function It_should_handle_custom_datetime_formats()
	{
		$formatter = new Prb_Logger_Formatter();
		$formatter->setDatetimeFormat( Prb::_String( '%Y-%m-%d %H:%M:%S' ) );
		
		$this->assertTrue(
		  $formatter->call(
		    Prb_Logger::severityLabel( Prb_Logger::WARN ),
		    Prb::_Time(),
		    Prb::_String( 'someprogram' ),
		    new Exception( 'derp exception' )
		  )->match( "/W, \[\d{4}-\d\d-\d\d\s\d\d:\d\d:\d\d#\d+\]  WARN -- someprogram.*derp exception/" )
		);
	} // It should handle custom datetime formats
}