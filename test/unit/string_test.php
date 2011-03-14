<?php


class Prb_StringTest extends PHPUnit_Framework_TestCase 
{
	/**
	 * It should upcase properly
	 * @author Joshua Morris
	 * @test
	 */
	public function It_should_upcase_properly()
	{
		$wrapper = Prb::_String( 'foO' );
		$this->assertEquals( 'FOO', $wrapper->upcase()->raw() );
	} // It should upcase properly
	
	/**
	 * It should downcase properly
	 * @author Joshua Morris
	 * @test
	 */
	public function It_should_downcase_properly()
	{
		$wrapper = Prb::_String( 'FoO' );
		$this->assertEquals( 'foo', $wrapper->downcase()->raw() );
	} // It should downcase properly
}