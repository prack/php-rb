<?php


class Prb_NumericTest extends PHPUnit_Framework_TestCase 
{
	/**
	 * It should throw an exception if instantiated with a non-numeric primitive
	 * @author Joshua Morris
	 * @test
	 */
	public function It_should_throw_an_exception_if_instantiated_with_a_non_numeric_primitive()
	{
		$this->setExpectedException( 'Prb_Exception_Type' );
		Prb::Num( 'foo' );
	} // It should throw an exception if instantiated with a non-numeric primitive
	
	/**
	 * It should provide access to its primitive
	 * @author Joshua Morris
	 * @test
	 */
	public function It_should_provide_access_to_its_primitive()
	{
		$numeric = Prb_Numeric::with( 13 );
		$this->assertEquals( 13, $numeric->raw() );
	} // It should provide access to its primitive
	
	/**
	 * It should convert to a Prb_String
	 * @author Joshua Morris
	 * @test
	 */
	public function It_should_convert_to_a_Prb_String()
	{
		$numeric = Prb::Num( 14 );
		$this->assertEquals( Prb::Str( '14' ), $numeric->toS() );
	} // It should convert to a Prb_String
	
	/**
	 * It should evaluate its value as a boolean
	 * @author Joshua Morris
	 * @test
	 */
	public function It_should_evaluate_its_value_as_a_boolean()
	{
		$numeric = Prb::Num( 1 );
		$this->assertTrue( is_bool( $numeric->truth() ) );
		$this->assertTrue( $numeric->truth() );
	} // It should evaluate its value as a boolean
	
	/**
	 * It should compare correctly at an object-level
	 * @author Joshua Morris
	 * @test
	 */
	public function It_should_compare_correctly_at_an_object_level()
	{
		$numeric = Prb::Num( 0 );
		$this->assertGreaterThanOrEqual(  1, $numeric->compare( Prb::Num( -1 ) ) );
		$this->assertLessThanOrEqual   ( -1, $numeric->compare( Prb::Num(  1 ) ) );
		$this->assertEquals            (  0, $numeric->compare( Prb::Num(  0 ) ) );
	} // It should compare correctly at an object-level
}