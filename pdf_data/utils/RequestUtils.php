<?php           

function ValuePOST($value_name, $value_def = '') {
    if(isset($_POST[$value_name])){
        return $_POST[$value_name];
    } else {
        return $value_def;
    }
}

function ValueGET($value_name, $value_def = '') {
    if(isset($_GET[$value_name])){
        return $_GET[$value_name];
    } else {
        return $value_def;
    }
}

function ValuePOSTNotEmpty($value_name) {
  return !empty($_POST[$value_name]);
}

function ValueGETNotEmpty($value_name) {
  return !empty($_GET[$value_name]);
}

function ValuePOSTIsEmpty($value_name) {
  return empty($_POST[$value_name]);
}

function ValueGETIsEmpty($value_name) {
  return empty($_GET[$value_name]);
}


?>