_This project is very much a work in progress. Feedback is welcome._


Prb
=====

Prb is a support library of object wrappers for PHP's primitives.

Most are convertible to their underlying type by calling the 'toN'
method. Documentation forthcoming.


Progress
========

Forthcoming.


Running Tests
=============

This project is designed using test-driven development. I've made the test
method names as descriptive and consistent as possible, so please check them
out as documentation until the project matures a bit more.

To run tests:
	git clone https://github.com/prack/php-ruby.git
	cd php-ruby
	phpunit

Of course, you must have PHPUnit installed, preferably alongside XDebug. I'm using
PHPUnit 3.5.


Beating up an Array
===================

	$ cd php-ruby
	$ php -a
	php > include "autoload.php";
	php > $array    = Prb::_Array( array( Prb::_String( 'foo' ), Prb::_String( 'bar' ) ) );
	php > $callback = create_function( '$acc, $item', 'return $acc->concat( $item );' );
	php > var_dump( $array->inject( Prb::_String( 'hello' ), $callback ) );
	object(Prb_String)#4 (1) {
	  ["string:private"]=>
	  string(11) "hellofoobar"
	}
	php > var_dump( $array->inject( Prb::_Array(), $callback ) );
	object(Prb_Array)#4 (1) {
	  ["array:protected"]=>
	  array(2) {
	    [0]=>
	    object(Prb_String)#1 (1) {
	      ["string:private"]=>
	      string(3) "foo"
	    }
	    [1]=>
	    object(Prb_String)#2 (1) {
	      ["string:private"]=>
	      string(3) "bar"
	    }
	  }
	}
	php > $callback = create_function( '$item', 'echo $item->raw();' );
	php > $array->each( $callback );
	foobar

...and so on. In exchange for the syntactic verbosity, you get a lot of flexibility.

Obviously, lambdas would make this sexier. But I'm stuck on PHP 5.2, and they may well
work out of the box. Give it a try.


Things I'm would love guidance on/help with
===========================================

* Reducing memory usage.
* Reducing the verbosity of the library.
* Algorithmic optimizations.


Acknowledgments
===============

I <3 Ruby.