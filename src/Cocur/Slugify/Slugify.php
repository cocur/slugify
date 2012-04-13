<?php

/**
 * The MIT License (MIT)
 * Copyright (c) 2012 Florian Eckerstorfer
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to
 * deal in the Software without restriction, including without limitation the
 * rights to use, copy, modify, merge, publish, distribute, sublicense, and/or
 * sell copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS
 * IN THE SOFTWARE.
 *
 * @package   org.cocur.slugify
 */

namespace Cocur\Slugify;

/**
 * @package   org.cocur.slugify
 * @author    Florian Eckerstorfer <florian@theroadtojoy.at>
 * @copyright 2012 Florian Eckerstorfer
 * @license   http://www.opensource.org/licenses/MIT The MIT License
 */
class Slugify {

	/** @var array */
	private $special_characters = array(
		'Ä'		=> 'AE',
		'ä'		=> 'ae',
		'Á'		=> 'A',
		'À'		=> 'A',
		'á'		=> 'a',
		'à'		=> 'a',
		'Â'		=> 'A',
		'â'		=> 'a',
		'É'		=> 'E',
		'È'		=> 'E',
		'é'		=> 'e',
		'è'		=> 'e',
		'Ê'		=> 'E',
		'ê'		=> 'e',
		'Ñ'		=> 'n',
		'ñ'		=> 'n',
		'Ö'		=> 'OE',
		'ö'		=> 'oe',
		'Ó'		=> 'O',
		'Ò'		=> 'O',
		'ó'		=> 'o',
		'ò'		=> 'o',
		'Ô'		=> 'O',
		'ô'		=> 'o',
		'ß'		=> 'ss',
		'Ü'		=> 'Ue',
		'ü'		=> 'ue',
		'Ú'		=> 'U',
		'Ù'		=> 'U',
		'ú'		=> 'u',
		'ù'		=> 'u',
		'Û'		=> 'U',
		'û'		=> 'u',
	);

	/**
	 * Takes a string and returns a slugified version of it. Slugs only consists of characters, numbers and the dash. They can be used in URLs. 
	 * @param  string $string String
	 * @return string         Slug
	 */
	public function slugify($string)
	{
		// Convert special characters like umlauts in an ASCII version.
		$string = str_replace(array_keys($this->special_characters), array_values($this->special_characters), $string);

		// Lower case.
		$string = strtolower($string);

		// Remove everything that is not a lower case character, number or dash.
		$string = preg_replace('/[^a-z0-9-]/', '-', $string);

		// Remove duplicate dashes
		$string = preg_replace('/-+/', '-', $string);

		// Remove dashes from the begining and the end.
		$string = preg_replace('/(^-)|(-$)/', '', $string);

		return $string;
	}

}
