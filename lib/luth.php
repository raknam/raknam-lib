<?php

	function luth($data) {
		if (!is_numeric($data)) return false;
		$data = str_split($data);
		
		$sum = 0;
		$odd = count($data) % 2 == 0;
		for($i = 0; $i < count($data); $i++) {
			$c = $data[count($data) - ($i + 1)];
			$val = ($odd ? $c : $c * 2);
			if ($val > 9) $val -= 9;
			$sum += $val;
			$odd = !$odd;
		}
		return $sum;
	}