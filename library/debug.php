<?php

/**
 * Global Debug function
 *
 * @param mixed $var
 * @param string $label
 * @param boolean $dump
 * @param boolean $showCallAt 是否显示调试调用位置
 *
 * @return void
 */
function debug($var,$label = null, $dump = false, $showCallAt = true) {
	if(is_null($var) || !isset($var) || is_bool($var)) {$dump = true;}
	if(is_string($var) && trim($var) == '') {$dump = true;}
	echo "<pre>$label\n";
	if($dump) {
		if(function_exists('xdebug_var_dump')) {
			xdebug_var_dump($var);
		} elseif(class_exists('Zend_Debug')) {
			Zend_Debug::dump($var, $label);
		} else {
			var_dump($var);
		}
	} else {
		print_r($var);
	}
	// 对象，则输出拥有的 methods
	if(is_object($var)) {
		echo "\n ___________________________ Methods: _________________________________\n";
		$methods = get_class_methods($var);
		foreach ($methods as $method) {
			echo "\n $method";
		}
        echo "\n ___________________________ Properties: _________________________________\n";
        $objectVars = get_object_vars($var);
        print_r(array_keys($objectVars));
	}
	echo "\n</pre>\n";

	if($showCallAt) {
        $idx = 5;
        $bt = debug_backtrace();

        // Check if thie function was called from the helpers shortcuts
        $caller = isset($bt[$idx]['function']) ? $bt[$idx]['function'] : '';
        if (!in_array($caller, array('debug', 'd'))) {
            $idx -= 2;
            $caller = isset($bt[$idx]['function']) ? $bt[$idx]['function'] : '';
            if (!in_array($caller, array('debug', 'd'))) {
                $idx = $idx - 2;
            }
        }

        $callFile = isset($bt[$idx]['file']) ? $bt[$idx]['file'] : null;
        $callLine = isset($bt[$idx]['line']) ? $bt[$idx]['line'] : null;
        echo "\n <i class='debug_trace'>DEBUG@ $callFile : #$callLine </i>";
    }
}

/**
 * debug() something, but exit() when finish.
 *
 * @param Mixed $var
 * @param string $label
 * @param boolean $dump
 */
function d($var,$label=null,$dump=false) {
	debug($var,$label,$dump);
	die();
}

function dbProfiler() {
	if(Zend_Registry::isRegistered('Profiler')) {
		$profiler = Zend_Registry::get('Profiler');
		/** @var $profiler Zend_Db_Profiler */
		$p = $profiler->getQueryProfiles();
		if(!empty($p)) {
			echo "<hr>";
			foreach ($p as $query) {
				/* @var $query Zend_Db_Profiler_Query */
				echo '[';
				$elapsed = number_format($query->getElapsedSecs(),4);
				if($elapsed>=0.01) {
					echo "<font color=red>".$elapsed."</font>";
				} else {
					echo $elapsed;
				}
				echo '] <i>';
				echo $query->getQuery()."</i><br/>\n";
			}
		}
	}
	return ;
}
