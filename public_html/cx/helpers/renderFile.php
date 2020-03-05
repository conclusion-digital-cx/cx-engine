<?php

return function ($file, $scope = "") {
    if (!file_exists($file)) return false;
    
    ob_start();
    include $file;
    return ob_get_clean();
};
