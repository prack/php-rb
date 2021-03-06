<?php


class Prb_IO_FileTest extends PHPUnit_Framework_TestCase 
{
	/**
	 * It should indicate writability correctly
	 * @author Joshua Morris
	 * @test
	 */
	public function It_should_indicate_writability_correctly()
	{
		$filepath = Prb_IO_Tempfile::generatePath();
		
		$file = Prb_IO::withFile( $filepath, Prb_IO_File::APPEND_AS_WRITE );
		$this->assertTrue( $file->isWritable() );
		$this->assertFalse( $file->isReadable() );
		$file->close();
		
		$file = Prb_IO::withFile( $filepath, Prb_IO_File::NO_CREATE_READ );
		$this->assertFalse( $file->isWritable() );
		$this->assertTrue( $file->isReadable() );
		$file->close();
		
		$file->unlink();
	} // It should indicate writability correctly
	
	/**
	 * It should throw an exception when file at specified path is inaccessible
	 * @author Joshua Morris
	 * @test
	 */
	public function It_should_throw_an_exception_when_file_at_specified_path_is_inaccessible()
	{
		$filepath = Prb_IO_Tempfile::generatePath().Prb::Time()->getSeconds();
		
		try
		{
			$file = Prb_IO::withFile( $filepath, Prb_IO_File::NO_CREATE_READ );
		}
		catch ( Prb_Exception_System_ErrnoENOENT $e1 ) {};
		
		if ( isset( $e1 ) )
			$this->assertRegExp( '/file not found for no-create open/', $e1->getMessage() );
		else
			$this->fail( "Excpected exception on no-create IO instantiation when file doesn't exist." );
	} // It should throw an exception when file at specified path is inaccessible
	
	/**
	 * It should know if it was opened in binary mode
	 * @author Joshua Morris
	 * @test
	 */
	public function It_should_know_if_it_was_opened_in_binary_mode()
	{
		$filepath = Prb_IO_Tempfile::generatePath();
		
		$file = Prb_IO::withFile( $filepath, Prb_IO_File::APPEND_AS_WRITE | Prb_IO_File::FORCE_TEXT );
		$this->assertFalse( $file->isBinMode() );
		$file->close();
		
		$file = Prb_IO::withFile( $filepath, Prb_IO_File::NO_CREATE_READ );
		$this->assertTrue( $file->isBinMode() );
		$file->close();
		
		$file->delete();
	} // It should know if it was opened in binary mode
}