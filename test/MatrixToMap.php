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

use Matrix\MatrixIterator;

class MatrixToMap{
	
	private $matrix;
	
	const HEADER_TYPE = 0;
	const ROW_TYPE = 1;
	const COLUMN_TYPE = 2;
	
	private $headerOffset = array(1,0);
	private $columnOffset = array(0,1);
	private $rowOffset = array(1,0);
	
	private $rowsInOneGroup = 0;
	
	public function __construct(MatrixIterator $matrix) {
		$this->matrix = $matrix;
	}
	
	public function getMap()
	{
		$map = new DebugTable();
		foreach($this->matrix as $keys => $values)
		{
            $this->setHeadersToMapByMatrix($map, $keys, $values);
            $this->setIntersectionToMapByMatrix($map, $keys, $values);
		}
		$map->displayMap();
		return $map;
	}
	
    private function setIntersectionToMapByMatrix(DebugTable $map, $keys, $values)
    {
        $offset = $this->getOffsetByMatrixKey($keys);
		$map->setValue($offset[0], $offset[1], implode(':', $values));
    }
    
    private function setHeadersToMapByMatrix(DebugTable $map, $keys, $values)
    {
        foreach($keys as $keyIndex => $indexPosition)
        {
            $indexValue = $values[$keyIndex];
            switch ($keyIndex)
			{
				case self::HEADER_TYPE:
					$headerHeaderPosition = $this->getHeaderPosition($indexPosition);
					$map->setValue($headerHeaderPosition[0], $headerHeaderPosition[1], $indexValue);
					break;
				case self::ROW_TYPE:
                    
					$rowHeaderPosition = $this->getRowHeaderPosition($indexPosition);
					$map->setValue($rowHeaderPosition[0], $rowHeaderPosition[1], $indexValue);
					break;
				case self::COLUMN_TYPE:
					$columnHeaderPosition = $this->getColumnPosition($indexPosition);
					$map->setValue($columnHeaderPosition[0], $columnHeaderPosition[1], $indexValue);
					break;
				default:
					throw new Exception('Unknown key Index type!');
					break;
			}
        }
    }
	
	private function getOffsetByMatrixKey($key)
	{
		$summaryOffset = array(0,0);
		foreach($key as $keyIndex => $indexPosition)
		{
			switch ($keyIndex)
			{
				case self::HEADER_TYPE:
					$headerOffset = $this->getHeaderOffset($indexPosition);
					$this->applyOffset($summaryOffset, $headerOffset);
					break;
				case self::ROW_TYPE:
					$this->calculateRowsInGroup($indexPosition);
					$rowOffset = $this->getRowOffset($indexPosition);
					$this->applyOffset($summaryOffset, $rowOffset);
					break;
				case self::COLUMN_TYPE:
					$columnOffset = $this->getColulmnOffset($indexPosition);
					$this->applyOffset($summaryOffset, $columnOffset);
					break;
				default:
					throw new Exception('Unknown key Index type!');
					break;
			}
		}
		return $summaryOffset;
	}
	
	private function calculateRowsInGroup($row)
	{
		// height used to calculate offset
		// so index 0 is offset of 1 row
		// index 6 means that there is 7 rows
		$row++;
		
		// we care only about the greatest row
		if($this->rowsInOneGroup < $row)
		{
			$this->rowsInOneGroup = $row;
		}
	}
	
	/**
	 * if index 2
	 * ofset should be 2 x row height + 3 x header height
	 */
	private function getHeaderOffset($headerIndex)
	{
		$resultOffset = array(0,0);
		// calculate offset from previous reporsts rows height
		// amout of prev reports is equal to index
		for($i = 0; $i < $headerIndex; $i++)
		{
			$this->applyOffset($resultOffset, array($this->rowsInOneGroup, 0));
		}
		
		// calculate offset from header
		// !important used "<=" instead of "<"
		for($i = 0; $i <= $headerIndex; $i++)
		{
			$this->applyOffset($resultOffset, $this->headerOffset);
		}
		
		return $resultOffset;
	}
	
	private function getRowOffset($rowOffset)
	{
		$resultOffset = array(0,0);
		
		for($i = 0; $i <= $rowOffset; $i++)
		{
			$this->applyOffset($resultOffset, $this->rowOffset);
		}
		
		return $resultOffset;
	}
	
	private function getColulmnOffset($columnOffset)
	{
		$resultOffset = array(0,0);
		
		// row header offset
		$this->applyOffset($resultOffset, array(0,1));
		
		for($i = 0; $i < $columnOffset; $i++)
		{
			$this->applyOffset($resultOffset, $this->columnOffset);
		}
		
		return $resultOffset;
	}
	
	private function applyOffset(& $addres, $offset)
	{
		if(count($addres) !== count($offset))
		{
			throw new Exception('Amount of indexes in adress are not equal to offset indexes amount!');
		}
		
		foreach($addres as $key => & $index)
		{
			if(!isset($offset[$key]))
			{
				throw new Exception('Wrong key in offset/adress!');
			}
			
			$index += $offset[$key];
		}
	}
}