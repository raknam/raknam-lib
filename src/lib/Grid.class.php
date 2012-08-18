<?php

	class Grid {
		public $height;
		public $width;
		
		private $grid;
		
		public function __construct($width, $height = null, $doInit = true) {
			if ($height == null) $height = $width;
			
			$this->height = $height;
			$this->width  = $width;
			
			if ($doInit)
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
		
		public static function importFromMatrix($matrix){
		    $width = strlen($matrix[0]);
		    $height = count($matrix);
		    $grid = new Grid($width, $height, false);
		    
		    $grid->grid = array();
			for($y = 0; $y < $width; $y++){
				$arr = array();
				for($x = 0; $x < $height; $x++){
					$arr[] = $matrix[$x][$y];
				}
				$grid->grid[] = $arr;
			}
			
			return $grid;
		}
		
		public function setValue($x, $y, $value){
			$this->grid[$x][$y] = $value;
		}
		
		public function exportToMatrix(){
		    $matrix = array();
		    for($y=0;$y<$this->width; $y++){
		        $line = array();
		        for($x = 0; $x < $this->height; $x++){
		            $line[] = (int)$this->grid[$x][$y];
		        }
		        $matrix[] = implode('',$line);
		    }
		    return $matrix;
		}
		
		public function exportToHTML(){
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