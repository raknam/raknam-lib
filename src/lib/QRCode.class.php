<?php

require_once('QRCodeGrid.class.php');
require_once('ReedSolomon.class.php');

	class QRCode {
		
		const   ALPHANUM_ENCODING_STRING = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ $%*+-./:";
		static  $CODEWORDS_SIZES = array(
			"1" => array(19,16,13,9), "2" => array(34,28,22,16),
		);
		static  $REMAINDER_BITS = array(
			"1"=>0,"2"=>7,"3"=>7,"4"=>7,"5"=>7,"6"=>7,
		);
		static  $CODEWORD_PADDING = array("11101100","00010001");
		static  $ERRORCODE_SIZES = array(
			"1" => array(7,10,13,17), "2" => array(10,16,22,28),
		);
		
		/**
		 * @var QRCodeGrid
		 */
		private $grid;
		
		/**
		 * @var ReedSolomon
		 */
		private $rs;
		
		public function __construct($version = 1){
			$this->grid = new QRCodeGrid($version);
			$this->grid->setValue(8,$this->grid->height - 8,1);
			$this->setFinders();
			$this->setTimePatterns();
			$this->rs = ReedSolomon::GetInstance();
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
			if ($version < 10) { $pad = 10; } else if ($version < 27) { $pad = 12; } else { $pad = 14; }
			$char_count = str_pad(decbin(strlen($data)), $pad, "0", STR_PAD_LEFT);
			
			//mode
			$mode = "0001"; //numeric
			
			$res = $this->getBitstream($mode, $char_count, $groups);
			
			$this->checkDataLength($mode, $data, $res, $pad);
			
			return $res;
		}
		
		public function encodeAlphaNumericData($data, $version) {
			$groups = str_split($data, 2);
			foreach($groups as &$grp){
				$padsize = isset($grp[1]) ? 11 : 6;
				$val = 0;
				for ($i=0; $i<2; $i++){
					if (!isset($grp[$i])) continue;
					$val+= $this->getAlphaNumCode($grp[$i]);
					if ($i == 0 && isset($grp[1])) $val = 45 * $val;
				}
				$grp = decbin($val);
				$grp = str_pad($grp, $padsize, "0", STR_PAD_LEFT);
			}
			
			//character count
			if ($version < 10) { $pad = 9; } else if ($version < 27) { $pad = 11; } else { $pad = 13; }
			$char_count = str_pad(decbin(strlen($data)), $pad, "0", STR_PAD_LEFT);
			
			//mode
			$mode = "0010"; //alphanumeric
			
			$res = $this->getBitstream($mode, $char_count, $groups);
			
			$this->checkDataLength($mode, $data, $res, $pad);
			
			return $res;
		}
		private function getAlphaNumCode($char) {
			return strpos(self::ALPHANUM_ENCODING_STRING, $char);
		}
		
		
		private function checkDataLength($mode, $data, $res, $char_count_bits){
			$d = strlen($data);
			switch($mode){
				case "0001": //numeric
					if ($d % 3 == 0) $r = 0; else if ($d % 3 == 1) $r = 4; else $r = 7;
					$b = 4 + $char_count_bits + 10 * floor($d / 3) + $r;
					break;
				case "0010": //alphanumeric
					$b = 4 + $char_count_bits + 11 * floor($d / 2) + 6 * ($d % 2);
					break;
			}
			
			if (strlen($res) != $b + 4)
				throw new Exception("Error on calculation - Length invalid ".strlen($data)." ".$b);
		}
		private function getBitstream(&$mode, &$char_count, &$data) {
			return sprintf("%s%s%s0000", $mode, $char_count, implode("",$data));
		}
		/**
		 * Generate codewords from bitstream
		 * @param String $data
		 * @param int $version
		 * @param char $quality
		 * @return array Return an array of decimal
		 */
		public function generateCodewordsFromBitstream(&$data, $version, $quality){
			$groups = str_split($data, 8);
			if (strlen($groups[count($groups) - 1]) < 8)
				$groups[count($groups) - 1] = str_pad($groups[count($groups) - 1], 8, "0");
				
			//Padding
			$odd = false;
			$max = self::$CODEWORDS_SIZES[$version][($quality == "L" ? 0 : ($quality == "M" ? 1 : ($quality == "Q" ? 2 : 3)))];
			for ($i = count($groups); $i < $max; $i++) {
				$groups[] = self::$CODEWORD_PADDING[$odd];
				$odd = !$odd;
			}

			foreach($groups as $k => $d) $groups[$k] = bindec($d);
			return $groups;
		}
		
		/**
		 * Calculate Error Corrections Bits using ReedSolomon Algorithm
		 * @param array $data
		 * @param int $version
		 * @param char $quality
		 * @return array
		 */
		public function calculateErrorCorrection($data, $version, $quality) {
		    $data = $this->rs->rs_encode_msg($data, 10);
		    return $data;
		}
		
		/**
		 * @param array $data
		 * @param int $version
		 * @param char $quality
		 * @return Grid
		 */
		public function generateGrid($data, $version, $quality){
    		for ($i = 0; $i < count($data); $i++){
    			$this->grid->setDataBlock($i, $data[$i]);
    		}
			return $this->grid;
		}
		/**** end ENCODING ****/
	}

	
	
	
	