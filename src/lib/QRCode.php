<?php

require_once('Grid.php');

	class QRCode {
		
		private $grid;
		
		public function __construct(){
			$this->grid = new Grid(21);
			//$this->grid->setValue(1,1,1);
			//$this->grid->setValue(10,10,1);
			$this->setFinders();
			$this->setTimePatterns();
		}
		
		//Version string "V-E" V=int(1~40) E=char(L,M,Q,H)
		private $_version;
		public function getVersion(){ return $this->_version; }
		public function setVersion($value){ $this->validateVersion($value); $this->_version = $value; }
		public function validateVersion($value){
			$tokens = explode('-',$value);
			if (count($tokens) != 2) throw new Exception('Invalid version');
			if (!is_numeric($tokens[0]) || round($tokens[0]) != $tokens[0]) throw new Exception('Invalid version');
			if ($tokens[0] < 1 || $tokens[0] > 40) throw new Exception('Invalid version');
			if (!in_array($tokens[1], array('L', 'M', 'Q', 'H'))) throw new Exception('Invalid version'); 
		}
		public function getVersionSize(){ $tokens = explode('-',$this->_version); return $tokens[0]; }
		public function getVersionType(){ $tokens = explode('-',$this->_version); return $tokens[1]; }
		
		public function validateData($value){
			/* Allowed chars are :
			 *  Numeric : 0-9 (max = 7089 in 40-L)
			 *  Alphanum : 0-9 A-Z a-z <space> $ % * + - . , / : (max = 4296 in 40-L)
			 *  8-bit : JIS 8-bit (max = 2953 in 40-L)
			 *  Kanji (max = 1817 in 40-L)
			 */
			
		}
		
		public function getSize(){ return $this->getVersionSize() * 4 + 17; }
		public function getErrorCorrectionLevel(){ switch($this->getVersionType()) {case"L":return 7;case"M":return 15;case"Q":return 25;case"H":return 30;}}
		
		private function setFinders(){
			$this->setFinder(0,0);
			$this->setFinder(0,$this->grid->width-7);
			$this->setFinder($this->grid->width-7,0);
		}
		private function setFinder($dx, $dy){
			for ($y = $dy; $y < 7+$dy; $y++){
				for ($x = $dx; $x < 7+$dx; $x++){
					if ($y > $dy+1 && $y < $dy+5 && $x > $dx+1 && $x < $dx+5){
						$this->grid->setValue($x, $y, 1);
					}else if ($y == $dy || $y == $dy+6 || $x == $dx || $x == $dx+6){
						$this->grid->setValue($x, $y, 1);
					}
				}
			}
		}
		private function setAlignment($dx, $dy){
			for ($y = $dy; $y < 5+$dy; $y++){
				for ($x = $dx; $x < 5+$dx; $x++){
					if ($y > $dy+1 && $y < $dy+3 && $x > $dx+1 && $x < $dx+3){
						$this->grid->setValue($x, $y, 1);
					}else if ($y == $dy || $y == $dy+4 || $x == $dx || $x == $dx+4){
						$this->grid->setValue($x, $y, 1);
					}
				}
			}
		}
		
		private function setTimePatterns(){
			//Horyzontal
			$val = true;
			for($x=8;$x<$this->grid->width-8;$x++){
				$this->grid->setValue($x,6,$val);
				$val=!$val;
			}
			//Vertical
			$val = true;
			for($y=8;$y<$this->grid->height-8;$y++){
				$this->grid->setValue(6,$y,$val);
				$val=!$val;
			}
		}
		
		
		public function export(){
			$this->grid->export();
		}
		
		
		/**** ENCODING ****/
		public function encodeNumericData($data, $version) {
			$groups = str_split($data, 3);
			foreach($groups as &$grp){
				$padsize = (strlen($grp) == 3 ? 10 : (strlen($grp) == 2 ? 7 : 4));
				$grp = decbin($grp);
				$grp = str_pad($grp, $padsize, "0", STR_PAD_LEFT);
			}
			
			//character count
			if ($version < 10) { $pad = 10; } else if ($version < 27) { $pad = 12; } else {	$pad = 14; }
			$char_count = str_pad(decbin(strlen($data)), $pad, "0", STR_PAD_LEFT);
			
			//mode
			$mode = "0001"; //numeric
			
			$res = $this->getFinalData($mode, $char_count, $groups);
			
			$this->checkNumericDataLength($data, $res, $pad);
			
			return $res;
		}
		private function checkNumericDataLength($data, $res, $char_count_bits){
			$d = strlen($data);
			if ($d % 3 == 0) $r = 0; else if ($d % 3 == 1) $r = 4; else $r = 7;
			$b = 4 + $char_count_bits + 10 * ((int)($d / 3)) + $r;
			
			if (strlen($res) != $b)
				throw new Exception("Error on calculation - Length invalid");
		}
		
		private function getFinalData(&$mode, &$char_count, &$data) {
			return sprintf("%s%s%s", $mode, $char_count, implode("",$data));
		}
		/**** end ENCODING ****/
		
	}
	
$qrcode = new QRCode();
$qrcode->export();

echo "<br />";
echo "01234567 :: ".$qrcode->encodeNumericData("01234567", 1);
echo "<br />";
echo "0123456789012345 :: ".$qrcode->encodeNumericData("0123456789012345", 1);
echo "<br />";



