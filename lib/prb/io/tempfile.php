<?php

// TODO: Document!
class Prb_IO_Tempfile extends Prb_IO_File
  implements Prb_I_ReadableStreamlike, Prb_I_WritableStreamlike
{
	// TODO: Document!
	static function generatePath( $prefix = null )
	{
		$prefix = is_null( $prefix ) ? Prb::Str( 'prack_tmp' ) : $prefix;
		return Prb::Str( tempnam( sys_get_temp_dir(), $prefix->raw() ) );
	}
	
	// TODO: Document!
	function __construct( $prefix = null )
	{
		$prefix = is_null( $prefix ) ? null : $prefix;
		parent::__construct( self::generatePath( $prefix ), parent::TRUNCATE_AND_READWRITE );
	}
	
	// TODO: Document!
	public function close( $unlink = false )
	{
		parent::close();
		if ( $unlink )
			unlink( $this->getPath() );
	}
}
