<?php

	require_once('Grid.class.php');

	class QRCodeGrid extends Grid {
		
		protected $_version;
		protected $_prohibitedCells;
		
		public function __construct($version){
			if ($version == 1) $size = 21;
			
			$this->_version = $version;
			parent::__construct($size);
			$this->initProhibitedCells();
		}

		private function initProhibitedCells(){
		    $this->_prohibitedCells = array();
		    //Finders
		    for ($i=0;$i<8;$i++){
		        for($j=0;$j<8;$j++){
    		        $this->_prohibitedCells[$i][$j] = true;
    		        $this->_prohibitedCells[$this->height - ($i+1)][$j] = true;
    		        $this->_prohibitedCells[$j][$this->width - ($i+1)] = true;
		        }
		    }
		    //TimePatterns
		    for ($i=7;$i<$this->width-7;$i++)
		        $this->_prohibitedCells[$i][6] = true;
		    for ($i=7;$i<$this->height-7;$i++)
		        $this->_prohibitedCells[6][$i] = true;
		    
		    //Alignments    
		    switch ($this->_version) {
				
		    }
		}
		
		public function setDataBlock($id, $value){
			switch ($this->_version) {
				case 1:
					$this->setDataBlockVersion1($id, $value);
					break;
			}
		}
		
		public function setDataBlockVersion1($id, $value){
			switch($id){
				case 0:  $this->setUpwardVerticalBlock(20,20,$value); break;
				case 1:  $this->setUpwardVerticalBlock(20,16,$value); break;
				case 2:  $this->setUpwardVerticalBlock(20,12,$value); break;
				
				case 3:  $this->setDownwardVerticalBlock(18,9,$value); break;
				case 4:  $this->setDownwardVerticalBlock(18,13,$value); break;
				case 5:  $this->setDownwardVerticalBlock(18,17,$value); break;
				
				case 6:  $this->setUpwardVerticalBlock(16,20,$value); break;
				case 7:  $this->setUpwardVerticalBlock(16,16,$value); break;
				case 8:  $this->setUpwardVerticalBlock(16,12,$value); break;
				
				case 9:  $this->setDownwardVerticalBlock(14,9,$value); break;
				case 10: $this->setDownwardVerticalBlock(14,13,$value); break;
				case 11: $this->setDownwardVerticalBlock(14,17,$value); break;
				
				case 12: $this->setUpwardVerticalBlock(12,20,$value); break;
				case 13: $this->setUpwardVerticalBlock(12,16,$value); break;
				case 14: $this->setUpwardVerticalBlock(12,12,$value); break;
				case 15: $this->setUpwardVerticalBlock(12,8,$value); break;
				case 16: $this->setUpwardVerticalBlock(12,3,$value); break;
				
				case 17: $this->setDownwardVerticalBlock(10,0,$value); break;
				case 18: $this->setDownwardVerticalBlock(10,4,$value); break;
				case 19: $this->setDownwardVerticalBlock(10,9,$value); break;
				case 20: $this->setDownwardVerticalBlock(10,13,$value); break;
				case 21: $this->setDownwardVerticalBlock(10,17,$value); break;
				
				case 22: $this->setUpwardVerticalBlock(8,12,$value); break;
				case 23: $this->setDownwardVerticalBlock(5,9,$value); break;
				case 24: $this->setUpwardVerticalBlock(3,12,$value); break;
				case 25: $this->setDownwardVerticalBlock(1,9,$value); break;
			}
		}
		
		public function setUpwardVerticalBlock($startx, $starty, $value){
			$string = str_pad(decbin($value), 8, "0", STR_PAD_LEFT);
			$j = 0;
			for ($i = 0; $i < strlen($string); $i++){
				$x = $startx; $y = $starty;
				
				$x = $x - ($j % 2);
				$y = $y - floor($j / 2);
				
				if (isset($this->_prohibitedCells[$x][$y])){
				    $j += 2;
				    $x = $x - ($j % 2);
				    $y = $y - floor($j / 2);
				}
				$this->grid[$x][$y] = $string[$i];
				$j++;
			}
		}
		
		public function setDownwardVerticalBlock($startx, $starty, $value){
			$string = str_pad(decbin($value), 8, "0", STR_PAD_LEFT);
			$j = 0;
			for ($i = 0; $i < strlen($string); $i++){
				$x = $startx; $y = $starty;
				
				$x = $x - ($j % 2);
				$y = $y + floor($j / 2);
				
				if (isset($this->_prohibitedCells[$x][$y])){
				    $j += 2;
				    $x = $x - ($j % 2);
				    $y = $y + floor($j / 2);
				}
				
				$this->grid[$x][$y] = $string[$i];
				$j++;
			}
		}
	}