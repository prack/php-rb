<?php

// TODO: Document!
class Prb_IO_String extends Prb_IO
  implements Prb_Interface_ReadableStreamlike, Prb_Interface_WritableStreamlike, Prb_Interface_LengthAware
{	
	const MAX_STRING_LENGTH = 1048576; // Maximum size in bytes of in-memory string buffer.
	
	private $string;
	
	// TODO: Document!
	function __construct( $string = null )
	{
		$string = is_null( $string ) ? Prb::_String() : $string;
		if ( !( $string instanceof Prb_Interface_Stringable ) )
			throw new Prb_Exception_Type( 'FAILSAFE: __construct $string is not a Prb_Interface_Stringable' );
		
		if ( $string->length() > self::MAX_STRING_LENGTH )
			throw new Prb_Exception_Argument( 'FAILSAFE: __construct $string too big for string io' );
		
		$this->string = $string;
		$stream = fopen( 'php://memory', 'w+b' );
		
		fputs( $stream, $string->toN() );
		rewind( $stream );
		
		parent::__construct( $stream, true );
	}
	
	// TODO: Document!
	public function read( $length = null, $buffer = null )
	{
		if ( is_null( $length ) )
			$adjusted_length = isset( $buffer ) ? self::MAX_STRING_LENGTH - $buffer->length() : self::MAX_STRING_LENGTH;
		else
			$adjusted_length = $length;
		
		$result = parent::read( $adjusted_length, $buffer );
		
		return ( is_null( $length ) && is_null( $result ) ) ? Prb::_String() : $result;
	}
	
	// TODO: Document!
	public function write( $buffer )
	{
		$result = parent::write( $buffer );
		$this->updateString();
		return $result;
	}
	
	// TODO: Document!
	public function length()
	{
		return $this->string->length();
	}
	
	// TODO: Document!
	public function string()
	{
		return clone $this->string;
	}
	
	// TODO: Document!
	public function close()
	{
		$this->updateString(); // update string one last time.
		return parent::close();
	}
	
	// TODO: Document!
	public function updateString()
	{
		if ( !$this->isReadable() )
			return;
		
		$stream = $this->getStream(); // from parent.
		$curpos = ftell( $stream );
		
		parent::rewind();
		$this->string = $this->read();
		
		fseek( $stream, $curpos );
	}
}
