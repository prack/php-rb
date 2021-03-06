<?php

// TODO: Document!
class Prb_HashTest extends PHPUnit_Framework_TestCase 
{
	private $items;
	
	// TODO: Document!
	function setUp()
	{
		$this->items = array();
	}
	
	/**
	 * It should handle each
	 * @author Joshua Morris
	 * @test
	 */
	public function It_should_handle_each()
	{
		$callback = array( $this, 'addToItems' );
		$items    = array( 'foo' => 'bar', 'baz' => 'bat' );
		
		$wrapper = Prb::Hsh( $items );
		$wrapper->each( $callback );
		
		$this->assertEquals( $items, $this->items );
	} // It should handle each
	
	/**
	 * This function is used as a callback for the above test.
	 */
	public function addToItems( $key, $item )
	{
		$this->items[ $key ] = $item;
	}
	
	/**
	 * It should handle set
	 * @author Joshua Morris
	 * @test
	 */
	public function It_should_handle_set()
	{
		$wrapper = Prb::Hsh( array( 'foo' => 'bar', 'baz' => 'bat' ) );
		$wrapper->set( 'foo', 'cow' );
		$wrapper->set( 'bar', 'bar' );
		$this->assertEquals( array( 'foo' => 'cow', 'baz' => 'bat', 'bar' => 'bar' ), $wrapper->raw() );
	} // It should handle set
	
	/**
	 * It should handle get
	 * @author Joshua Morris
	 * @test
	 */
	public function It_should_handle_get()
	{
		$wrapper = Prb::Hsh( array( 'foo' => 'bar', 'baz' => 'bat', 'cow' => 'cud' ) );
		$this->assertEquals( 'bar', $wrapper->get( 'foo' ) );
		$this->assertEquals( 'bat', $wrapper->get( 'baz' ) );
		$this->assertEquals( 'cud', $wrapper->get( 'cow' ) );
		
		$wrapper->setDefault( Prb::Str( 'default value' ) );
		$this->assertEquals( $wrapper->getDefault(), $wrapper->get( 'Macguyver' ) );
	} // It should handle get
	
	/**
	 * It should know if it's empty
	 * @author Joshua Morris
	 * @test
	 */
	public function It_should_know_if_it_s_empty()
	{
		$wrapper = Prb::Hsh( array() );
		$this->assertTrue( $wrapper->isEmpty() );
		
		$wrapper->set( 'foo', 'bar' );
		
		$this->assertEquals( 'bar', $wrapper->delete( 'foo' ) );
		$this->assertTrue( $wrapper->isEmpty() );
	} // It should know if it's empty
	
		/**
	 * It should handle slice
	 * @author Joshua Morris
	 * @test
	 */
	public function It_should_handle_slice()
	{
		$wrapper = Prb::Hsh( array( 'foo' => 'bar', 'baz' => 'bat', 'blarn' => 'bjork' ) );
		
		$this->assertEquals( Prb::Ary( array( 'bar', 'bat' ) ), $wrapper->slice( 'foo', 'baz' ) );
		$this->assertEquals( Prb::Ary( array( 'bar', null ) ), $wrapper->slice( 'foo', 'wilhelm' ) );
	} // It should handle slice
	
	/**
	 * It should handle inject
	 * @author Joshua Morris
	 * @test
	 */
	public function It_should_handle_inject()
	{
		$wrapper = Prb::Hsh( array( 'foo' => 'bar', 'baz' => 'bat', 'blarn' => 'bjork' ) );
		
		$callback = create_function( '$accumulator,$key,$value', 'return $accumulator.$value;' );
		$this->assertEquals( 'hibarbatbjork', $wrapper->inject( 'hi', $callback ) );
		
		$wrapper = Prb::Hsh( array(
		  'foo' => Prb::Ary( array( 'cow', 'cud' ) ),
		  'baz' => Prb::Ary( array( 'lol', 'wut' ) )
		) );
		$callback = create_function( '$accumulator,$left,$right', 'return $accumulator.$left.$right;' );
		$this->assertEquals( 'hicowcudlolwut', $wrapper->inject( 'hi', $callback ) );
	} // It should handle inject
}