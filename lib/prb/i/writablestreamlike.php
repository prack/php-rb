<?php

// TODO: Document!
interface Prb_I_WritableStreamlike
{
	public function puts();
	public function write( $string );
	public function flush();
}