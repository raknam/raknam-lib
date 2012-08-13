<?php

	class Grid {
		public $height;
		public $width;
		
		private $grid;
		
		public function __construct($width, $height = null) {
			if ($height == null) $height = $width;
			
			$this->height = $height;
			$this->width  = $width;
			
			$this->initgrid();
		}
		
		private function initGrid(){
			$this->grid = array();
			for($y = 0; $y < $this->width; $y++){
				$arr = array();
				for($x = 0; $x < $this->height; $x++){
					$arr[] = 0;
				}
				$this->grid[] = $arr;
			}
		}
		
		public function setValue($x, $y, $value){
			$this->grid[$x][$y] = $value;
		}
		
		public function export(){
			echo '<table cellspacing="0" cellpadding="0">';
			for($y=0;$y<$this->width; $y++){
				echo '<tr>';
				for($x = 0; $x < $this->height; $x++){
					echo '<td style="'.($this->grid[$x][$y]?'background-color:black':'').';height:5px;width:5px"></td>';
				}
				echo '</tr>';
			}
			echo '</table>';
		}
	}