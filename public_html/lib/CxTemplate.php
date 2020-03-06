<?php

class CxTemplate {
    function render($view, $slots = []) {
        global $db, $service;
        /**
		 * Template Helpers
		 */
		$asset = function ($dir = '') {
			return str_replace(APP, "", $dir);
		};

		$slot = function ($name) use ($slots) {
			$scope = isset($slots[$name]) ? $slots[$name] : null;
			// echo $name;
			// getCx()->debug($scope);

			global $db, $service;
			
			if(is_string($scope)) {
				echo $scope;
			} else {
				$type = $scope[0];
				// getCx()->debug($type);

				// ['file' => 'view.php']
				if($scope['file']) {
					include $scope['file'];
				}

				// ['file', 'view.php']
				if($type === 'file') {
					include $scope[1];
				}
				echo isset($scope[$name]) ? $scope[$name] : '';
			}
        };
        
        $region = $slot;

        include $view;
    }
}