<?php           

//if (file_exists(DIR_APPLICATION.'controller/ovologics/log.txt')) {    
//    unlink(DIR_APPLICATION.'controller/ovologics/log.txt');
//}


// turn on handling all errors, except E_NOTICE
error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', 1);
 
// our error handler
function errorHandler($level, $message, $file, $line, $context) {
    switch ($level) {
        case E_WARNING:
            $type = 'Warning';
            break;
        case E_NOTICE:
            $type = 'Notice';
            break;
        default;
            $type = 'PHP';
    }
    
    if (strpos($file, 'mpdf_lib') === false) { //excluding mPDF errors
        LogInfo('errorHandler ---------------------------------------------------------');
        LogInfo("$type: $message");
        LogInfo("File: $file:$line");
        LogInfo("Context: $". join(', $', array_keys($context)));
        LogInfo('errorHandler ---------------------------------------------------------');
    }
    return true;
}

//registration our handler for all error types
set_error_handler('errorHandler', E_ALL);

    
function Shutdown() {
    $error = error_get_last();
    if ( is_array($error) ) {
        if ( array_key_exists('type', $error) ) {
            if ( ($error['type'] === E_ERROR) or 
                 ($error['type'] === E_PARSE) or 
                 ($error['type'] === E_CORE_ERROR) or 
                 ($error['type'] === E_COMPILE_ERROR) ) {
                LogInfo('Shutdown ---------------------------------------------------------');
                LogInfo(json_encode($error));
                LogInfo('Shutdown ---------------------------------------------------------');
            }
        }
    }
}
register_shutdown_function('Shutdown');    


function ExcepcionHandler($excepcion) {
    LogInfo('Excepcion ---------------------------------------------------------');
    LogInfo($excepcion->getMessage());
    LogInfo('Excepcion ---------------------------------------------------------');
}
set_exception_handler('ExcepcionHandler');


function LogInfo($text) {
  file_put_contents(PDF_CATALOG__PLUGIN_DIR.'/log.txt', date("Y-m-d H:i:s").'    '.$text."\n", FILE_APPEND | LOCK_EX);
}

?>