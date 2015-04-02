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


namespace MatrixTest;

class DebugTable{
	
	protected $width;
	protected $map;
	
	public function construct()
	{
		$this->width = 0;
		$this->map = array();
	}
	
	public function getMap()
	{
		return $this->map;
	}
	
	public function displayMap()
	{
		$map = $this->getMap();
		foreach($map as $row)
		{
			echo implode(' | ', $row);
			echo '<br/>';
		}
	}
	
	public function setValue($row, $column, $value)
	{
		$this->initialize($row, $column);
		
		$this->map[$row][$column] = $value;
	}
	
	private function isInitialized($row, $column)
	{
		if(!isset($this->map[$row]))
		{
			return false;
		}
		if(!isset($this->map[$row][$column]))
		{
			return false;
		}
	}
	
	private function initialize($row, $column)
	{
		if(!isset($this->map[$row]))
		{
			$this->map[$row] = array();
		}
		if(!isset($this->map[$row][$column]))
		{
			if($column > $this->width)
			{
				$this->width = $column;
			}
			
			$iCurrentWidth = count($this->map[$row]);
			
			for($i = $iCurrentWidth; $i <= $this->width; $i++)
			{
				$this->map[$row] [$i] = 'NULL';
			}
		}
	}
}

