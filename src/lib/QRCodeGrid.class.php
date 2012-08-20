<?php

	require_once('Grid.class.php');

	class QRCodeGrid extends Grid {
		
		protected $_version;
		
		public function __construct($version){
			if ($version == 1) $size = 21;
			
			$this->_version = $version;
			parent::__construct($size);
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
				
				//15 will hurt fixed
			}
		}
		
		public function setUpwardVerticalBlock($startx, $starty, $value){
			$string = str_pad(decbin($value), 8, "0", STR_PAD_LEFT);
			for ($i = 0; $i < strlen($string); $i++){
				$x = $startx; $y = $starty;
				
				$x = $x - ($i % 2);
				$y = $y - floor($i / 2);
				
				$this->grid[$x][$y] = $string[$i];
				//echo "putting [$x][$y] to {$string[$i]}<br />";
			}
		}
		
		public function setDownwardVerticalBlock($startx, $starty, $value){
			$string = str_pad(decbin($value), 8, "0", STR_PAD_LEFT);
			for ($i = 0; $i < strlen($string); $i++){
				$x = $startx; $y = $starty;
				
				$x = $x - ($i % 2);
				$y = $y + floor($i / 2);
				
				$this->grid[$x][$y] = $string[$i];
				//echo "putting [$x][$y] to {$string[$i]}<br />";
			}
		}
	}