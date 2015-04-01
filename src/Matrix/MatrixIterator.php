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
	private $reversedIterators;
	private $reversetCachedKeys;
	
	private $debug;
	
	public function __construct()
	{
		$this->iterators = [];
		$this->reversedIterators = [];
		
		$this->debug = ['next' => 0, 'valid' => 0, 'cache' => 0];
	}
	
	public function getDebug()
	{
		return $this->debug;
	}
	
	public function attachIterator(ArrayIterator  $iter)
	{
		$this->iterators []= $iter;
		$this->reverseIterators();
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
			$this->debug ['valid']++;
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
		$arrayRoot = count($this->reversedIterators) - 1;
		
		foreach($this->reversedIterators as $current => $iter)
		{
			$this->debug ['next']++;
			if($this->nextReversedOffsetExists($current))
			{
				$iter->next();
				return;
			}
			elseif($current !== $arrayRoot)
			{
				$iter->rewind();
			}
			elseif($current === $arrayRoot)
			{
				$iter->next();
			}
			else
			{
				// unreachable
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
	
	private function initReversedCachedKeys($iteratorIndex)
	{
		if(!isset($this->reversetCachedKeys[$iteratorIndex]))
		{
			$this->reversetCachedKeys[$iteratorIndex] = [];
			foreach($this->reversedIterators as $key => $notUsed)
			{
				$this->debug ['cache']++;
				$this->reversetCachedKeys[$iteratorIndex] []= $key;
			}
		}
	}
	
	private function getReversedCachedKeys($iteratorIndex)
	{
		$this->initReversedCachedKeys($iteratorIndex);
		return $this->reversetCachedKeys[$iteratorIndex];
	}
	
	private function nextReversedOffsetExists($iteratorIndex)
	{
		$keys = $this->getReversedCachedKeys($iteratorIndex);
		end($keys);
		if(key($keys) === $this->reversedIterators[$iteratorIndex]->key())
		{
			return false;
		}
		else 
		{
			return true;
		}
	}
	
	private function reverseIterators()
	{
		$this->reversedIterators = array_reverse($this->iterators);
	}
}
