<?php

/* 
 * The MIT License
 *
 * Copyright 2015 Semyon Radionov <Simon.Radionov@gmail.com>.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

require_once 'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

use Matrix\MatrixIterator;

function generate($count)
{
	$result = [];
	for($i = 0; $i < $count; $i++)
	{
		$result []= $i;
	}
	return $result;
}
$bigArray = new ArrayIterator(generate(20));
$bigArray2 = new ArrayIterator(generate(20));
$bigArray3 = new ArrayIterator(generate(10));

$bigIterator = new MatrixIterator();
$bigIterator->attachIterator($bigArray);
$bigIterator->attachIterator($bigArray2);
$bigIterator->attachIterator($bigArray3);

$start = time();
foreach($bigIterator as $key => $value)
{
	// do some stuff
}
echo 'END: ';
var_dump($bigIterator->getDebug());
var_dump(time() - $start);


$ducks = new ArrayIterator(['duck1', 'duck2']);
$dogs = new ArrayIterator(['dog1', 'dog2', 'dog3']);
$cats = new ArrayIterator(['cat1', 'cat2', 'cat3', 'cat4']);

$iterator = new MatrixIterator();
$iterator->attachIterator($ducks);
$iterator->attachIterator($dogs);
$iterator->attachIterator($cats);


foreach($iterator as $key => $value)
{
	echo (implode(' | ', $value));
	echo '<br/>';
}
var_dump($iterator->getCache());

foreach($iterator as $key => $value)
{
    echo (implode(' | ', $key));
	echo '<br/>';
}