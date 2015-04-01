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

namespace Matrix;

use Iterator;
use ArrayIterator;

class MatrixIterator implements Iterator{
	
	private $iterators;
	
	public function __construct()
	{
		$this->iterators = [];
	}
	
	public function attachIterator(ArrayIterator  $iter)
	{
		$this->iterators []= $iter;
	}
	
	public function rewind()
	{
		foreach($this->iterators as $iter)
		{
			$iter->rewind();
		}
	}
	
	public function valid()
	{
		$allWalid = true;
		$oneWalid = false;
		
		foreach($this->iterators as $iter)
		{
			if($iter->valid())
			{
				$oneWalid = true;
			}
			else
			{
				$allWalid = false;
			}			
		}
		
		return $allWalid;
	}
	
	public function next()
	{
		foreach($this->iterators as $iter)
		{
			if($this->nextOffsetExists($iter))
			{
				$iter->next();
				return;
			}
		}
		
		// all in the end prevent inf loop debug
		$iter->next();
	}
	
	public function current()
	{
		$retval = [];
		
		foreach($this->iterators as $iter)
		{
			if($iter->valid())
			{
				$retval []= $iter->current();
			}
			else
			{
				$retval []= null;
			}
		}
		
		return $retval;
	}
	
	public function key()
	{
		if(!count($this->iterators))
		{
			return false;
		}
		
		$retval = [];
		
		foreach($this->iterators as $iter)
		{
			if($iter->valid())
			{
				$retval []= $iter->key();
			}
			else
			{
				$retval []= null;
			}
		}
		
		return $retval;
	}
	
	private function nextOffsetExists(ArrayIterator $iterator)
	{
		$result = false;
		$currentKeyFound = false;
		$copyedIterator = new ArrayIterator($iterator->getArrayCopy());
		while($copyedIterator->valid())
		{
			if($copyedIterator->key() === $iterator->key())
			{
				$currentKeyFound = true;
			}
			
			$copyedIterator->next();
			
			if($currentKeyFound)
			{
				$result = $copyedIterator->valid();
				break;
			}
		}
		return $result;
	}
}
