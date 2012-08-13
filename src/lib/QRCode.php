<?php

	class QRCode {
		
		public function __construct(){
			
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
		
		
	}