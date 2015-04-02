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
	private $cachedKeys;
	private $valid;
	
	public function __construct()
	{
		$this->iterators = [];
		$this->cachedKeys = [];
		$this->valid = true;
	}
	
	public function attachIterator(ArrayIterator  $iter)
	{
		$this->iterators []= $iter;
		$this->cachedKeys = [];
	}
	
	public function rewind()
	{
		$this->valid = true;
		foreach($this->iterators as $iter)
		{
			$iter->rewind();
		}
	}
	
	public function valid()
	{
		if(!count($this->iterators))
		{
			return false;
		}
		return $this->valid;
	}
	
	public function next()
	{
		$endKey = count($this->iterators) - 1;
		$arrayRoot = 0;
		$this->valid = false;
		
		for($current = $endKey; $current >= 0; $current--)
		{
			$iter = $this->iterators[$current];
			if($this->nextExists($current))
			{
				$iter->next();
				$this->valid = true;
				return;
			}
			elseif($current !== $arrayRoot)
			{
				$iter->rewind();
			}
			elseif($current === $arrayRoot)
			{
				$iter->next();
				$this->valid = false;
			}
			else
			{
				throw new Exception('Internal Error case should be unreachable!');
			}
		}
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
	
	private function initCachedKeys($iteratorIndex)
	{
		if(!isset($this->cachedKeys[$iteratorIndex]))
		{
			$this->cachedKeys[$iteratorIndex] = [];
			$copy = $this->iterators[$iteratorIndex]->getArrayCopy();
			foreach($copy as $key => $notUsed)
			{
				$this->cachedKeys[$iteratorIndex] []= $key;
			}
		}
	}
	
	private function getCachedKeys($offset)
	{
		$this->initCachedKeys($offset);
		return $this->cachedKeys[$offset];
	}
	
	private function nextExists($offset)
	{
		$keys = $this->getCachedKeys($offset);
		end($keys);		
		if(key($keys) === $this->iterators[$offset]->key())
		{
			return false;
		}
		else 
		{
			return true;
		}
	}
}
