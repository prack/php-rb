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
		
		$this->assertRegExp(
			"/W, \[\d{4}-\d\d-\d\dT\d\d:\d\d:\d\d\.\d{6}#\d+\]  WARN -- someprogram/",
		  $formatter->call(
		    Prb_Logger::severityLabel( Prb_Logger::WARN ),
		    Prb::Time(),
		    'someprogram',
		    array( 'primitive' )
		  )
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
		$formatter->setDatetimeFormat( '%Y-%m-%d %H:%M:%S' );
		
		$this->assertRegExp(
		  "/W, \[\d{4}-\d\d-\d\d\s\d\d:\d\d:\d\d#\d+\]  WARN -- someprogram.*derp exception/",
		  $formatter->call(
		    Prb_Logger::severityLabel( Prb_Logger::WARN ),
		    Prb::Time(),
		    'someprogram',
		    new Exception( 'derp exception' )
		  )
		);
	} // It should handle custom datetime formats
}