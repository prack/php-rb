<?php

// TODO: Document!
interface Prb_I_ReadableStreamlike
{
	public function gets();
	public function read( $length = null, &$buffer = null );
	public function each( $callback );
	public function rewind();
}