<?php

function GenerateDBBackup(){

    header('Content-Type: text/plain');
    header('Content-Disposition: attachment; filename="'.DB_DATABASE.'-backup-' . date("Y-m-d-His") . '.sql"');
     
    //echo backup_tables('localhost', $DBUSER, $DBPASSWD, $DATABASE);

    //echo backup_tables(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
    $host = DB_HOSTNAME;
    $user = DB_USERNAME;
    $pass = DB_PASSWORD;
    $name = DB_DATABASE;
    $tables = '*';
     
    /* backup the db OR just a table */
    //function backup_tables($host, $user, $pass, $name, $tables = '*')
   
    $return  = "\n\nSET FOREIGN_KEY_CHECKS=0;\n";
    $return .= "SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';\n\n";
    
    $link = mysql_connect($host,$user,$pass);
    mysql_select_db($name,$link);
    mysql_query('SET NAMES utf8');
    
    
     
    //get all of the tables
    if($tables == '*') {
        $tables = array();
        $result = mysql_query('SHOW TABLES');
        while($row = mysql_fetch_row($result)) {
            $tables[] = $row[0];
        }
    }
    else {
        $tables = is_array($tables) ? $tables : explode(',', $tables);
    }
     
    //cycle through
    foreach($tables as $table)
    {
        $result = mysql_query('SELECT * FROM `'.$table.'`');
        $num_fields = mysql_num_fields($result);
         
        $return .= 'DROP TABLE IF EXISTS `'.$table.'`;';
        $row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE `'.$table.'`'));
        $return .= "\n\n".$row2[1].";\n\n";
         
        for ($i = 0; $i < $num_fields; $i++) {
            while($row = mysql_fetch_row($result)) {
                $return.= 'INSERT INTO `'.$table.'` VALUES(';
                for($j=0; $j < $num_fields; $j++) {                  
                    if(is_null($row[$j])) {
                        $return .= 'NULL';
                    }
                    else {
                        //$row[$j] = addslashes($row[$j]);
                        //$row[$j] = preg_replace("@\0@","\\0",$row[$j]);
                        $row[$j] = str_replace("'","\'",$row[$j]);
                        $row[$j] = str_replace('"','""',$row[$j]);
                        $row[$j] = preg_replace("@\t@","\\t",$row[$j]);
                        $row[$j] = preg_replace("@\r@","\\r",$row[$j]);
                        $row[$j] = preg_replace("@\n@","\\n",$row[$j]);
                        //$row[$j] = preg_replace("@\b@","\\b",$row[$j]);
                        $row[$j] = str_replace('\\', '\\\\', $row[$j]);
                        $return .= '"'.$row[$j].'"';
                    }
                    if($j < ($num_fields-1)) { $return .= ','; }
                }
                $return .= ");\n";
            }
        }
        $return.="\n\n\n";
    }   
    echo $return;
}

?>