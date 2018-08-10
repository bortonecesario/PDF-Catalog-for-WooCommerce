<?php

// ---------------------------------------------------------------------------->
//   Functions for browser recognition
// ---------------------------------------------------------------------------->


// Content -------------------------------------------------------------------->
// i_browser_is_ie()                - check if browser is IE
// i_browser_abbr()                 - returns abbreviation and version of currently used browser 
// i_browser_os()                   - detect user OS trow browser user agent
// i_browser_id()                   - get browser id
// i_browser_ver()                  - get browser verion
// ---------------------------------------------------------------------------->


define('BROWSER_IE',    'msie');
define('BROWSER_IE_ID', 1);


// i_browser_is_ie()
//   Check if browser is IE
// parameters:
//   -
// return:
//   true - browser is IE
//   false - error
function i_browser_is_ie(){

    $browser_id = i_browser_id();
    if( $browser_id == BROWSER_IE_ID ){
        return true;
    }
    else{
        return false;
    }
} // i_browser_is_ie()


// i_browser_class()
//   Create scc browser class
// parameters:
//   -
// return:
//   string - css class
function i_browser_class(){
    
    $body_class = substr(i_browser_abbr(), 0, 2);
    $full_class = trim(substr(i_browser_abbr(), 0, 5));
    $add_class = '';
     
    if( $full_class == 'IE 7' ) $add_class = ' IE_7';
    elseif( $full_class == 'IE 8' ) $add_class = ' IE_8';
    elseif( $full_class == 'IE 9' ) $add_class = ' IE_9';
    elseif( $full_class == 'IE 10' ) $add_class = ' IE_10';
    elseif( $full_class == 'IE 11' ) $add_class = ' IE_11';
    $body_class .= ( i_browser_is_mobile() ) ? ' mobile' : '';
    
    return $body_class . $add_class;
}


// i_browser_is_mobile()
//   Check if user is accesing form mobile phone
// parameters:
//   -
// return:
//   true/false - is or not mobile
// notes: 
//   -
function i_browser_is_mobile(){

    $useragent = $_SERVER['HTTP_USER_AGENT'];
    $accept    = $_SERVER['HTTP_ACCEPT'];
    $is_mob = false;
    
    $mobile_devices = 'mobile|ipad|ipod|iphone|android|opera mini|blackberry|palm|windows ce|
                       opera mobi|smartphone|iemobile|mobileexplorer|openwave|operamini|
                       elaine|palmsource|digital paths|avantgo|xiino|palmscape|nokia|
                       ericsson|motorola|symbos|mobi';
                       
    if( preg_match('/' . $mobile_devices . '/i', $useragent) ){
        $is_mob = true;
    }
    
    if( !$is_mob ){
        if(    isset($_SERVER['HTTP_X_WAP_PROFILE']) 
            || isset($_SERVER['HTTP_PROFILE']) 
            || strpos($accept,'text/vnd.wap.wml') !== false
            || strpos($accept,'application/vnd.wap.xhtml+xml') ){
            $is_mob = true;
        }
    }
    
    return $is_mob;
} // i_browser_is_mobile


// i_browser_abbr()
//   returns abbreviation and version of currently used browser 
// parameters:
//   - 
// return:
//   string - abbreviation and version
// notes: 
//   -
function i_browser_abbr(){
    
    $debug = 0;
    
    //delimiter for abbr and version
    $d = ' ';
    $max_ver = '';
    $agent = $_SERVER['HTTP_USER_AGENT'];
    // $agent = 'Mozilla/5.0 (compatible; MSIE 10.6; Windows NT 6.1; Trident/5.0; InfoPath.2; SLCC1; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729; .NET CLR 2.0.50727) 3gpp-gba UNTRUSTED/1.0';
    // for tests , manualy set agent
    // $agent = 'For safari we need this:  Version/3.1.2 Safari/525.21 ,that\'s all ';
    
    $browsers = array(
                      'msie',
                      'firefox',
                      'opera',
                      //note: $_SERVER['HTTP_USER_AGENT'] for 'chrome' contains also name 'safari', but it is different browsers
                      'maxthon',
                      'chrome',
                      
                );
    
    
    //IE (Internet Explorer)
    if( preg_match('/(msie\s[.0-9]+)/i', $agent, $matches) === 1 ){
        $abbr = 'IE';
        list(,$ver) = explode(' ', $matches[0]);
        // Maxthon
        if( preg_match('/(Maxthon\/[.0-9]*)/i', $agent, $matches) === 1){
            $ver .= $d . '(' . 'Maxhton';
            list(,$max_ver) = explode('/', $matches[0]);
            $ver .= $d . $max_ver . ')'; 
        }
        $res = $abbr . $d . $ver;
    }
    elseif(preg_match('/Trident.*rv.(\\d+)\\.\\d+/i', $agent, $matches) === 1 ){ // special case for IE 11
        
        // sample ie header
        // Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko
        
        $abbr = 'IE';
        $ver  = ( isset($matches[1]) ) ? $matches[1] : '11';
        $res = $abbr . $d . $ver;
        
    }
    //FF (FireFox)
    elseif( preg_match('/(firefox\/[.0-9]*)/i', $agent, $matches) === 1 ){
        $abbr = 'FF';
        list(,$ver) = explode('/', $matches[0]);
        $res = $abbr . $d . $ver;
    }
    //OP (Opera)
    elseif( preg_match('/(opera\/[.0-9]*)/i', $agent, $matches) === 1 ){
        $abbr = 'OP';
        list(,$ver) = explode('/', $matches[0]);
        $res = $abbr . $d . $ver;
    }
    //GC (Google Chrome)
    elseif( preg_match('/(chrome\/[.0-9]*)/i', $agent, $matches) === 1 ){
        $abbr = 'GC';
        list(,$ver) = explode('/', $matches[0]);
        // Maxthon
        if( preg_match('/(Maxthon\/[.0-9]*)/i', $agent, $matches) === 1){
            $ver .= $d . '(' . 'Maxhton';
            list(,$max_ver) = explode('/', $matches[0]);
            $ver .= $d . $max_ver . ')'; 
        }
        $res = $abbr . $d . $ver;
        // d_echo($res); die();
    }
    //SF (Safari)
    elseif( preg_match('/(version\/[.0-9]* safari\/[.0-9]*)/i', $agent, $matches) === 1 ){
        $abbr = 'SF';
        
        list($version, $safari) = explode(' ', $matches[0]);
        list(,$ver) = explode('/', $version);
        list(,$sub_ver) = explode('/', $safari);
        $res = $abbr . $d . $ver . '(' . $sub_ver . ')';
    }
    //SF (Safari mobile)
    elseif( preg_match('/(version\/[.0-9]* Mobile Safari)/i', $agent, $matches) === 1 ){
        $abbr = 'SF Mobile';
        
        list($version, $safari) = explode(' ', $matches[0]);
        list(,$ver) = explode('/', $version);
        //list(,$sub_ver) = explode('/', $safari);
        $res = $abbr . $d . $ver;
    }
    //OTH (Others)
    else{
        $res = 'Other (' . $agent . ')';
    }
    
    if( strlen($res) > 50 ) $res = substr($res, 0, 50); // cut to 50 symbols
    
    if( $debug ){
        print_pre($agent);
        print_pre('matches');
        print_pre($matches);
        print_pre($res);
    
    }
    
    return $res;
   
} // i_browser_abbr


// i_browser_os()
//   detect user OS trow browser user agent
// parameters:
//   -
// return:        
//   string - OS type
function i_browser_os(){

        $os = 'Unknown';
        $useragent = $_SERVER['HTTP_USER_AGENT'];
        
        
        // Check if OS is windows --------------------------------------------->
        if( strpos($useragent, "Win") !== false ){
            $windows_os = array('NT 6.2'  => 'Windows 8',
                                'NT 6.1'  => 'Windows 7',
                                'NT 6.0'  => 'Windows Vista',
                                'NT 5.2'  => 'Windows Server 2003 or XPx64',
                                'NT 5.1'  => 'Windows XP',
                                'Win32'   => 'Windows XP',
                                'XP'      => 'Windows XP',
                                'NT 5.0'  => 'Windows 2000',
                                'NT 4.0'  => 'Windows NT',
                                'NT 3.5'  => 'Windows NT',
                                'Me'      => 'Windows Me',
                                '98'      => 'Windows 98',
                                '95'      => 'Windows 95',
                                'Win'     => 'Unknown Windows OS',
                                'Windows' => 'Unknown Windows OS'
                          );
                          
            $ci = count($windows_os);
            $keys = array_keys($windows_os);
            for( $i = 0; $i < $ci; $i++ ){
                if( strpos($useragent, $keys[$i]) !== false ){
                    // we have a match, set browser name
                    $os = $windows_os[$keys[$i]];
                    break;
                }
            }
        } // if( ...
        // -------------------------------------------------------------------->
        
        
        // if we don't have os name check if it's in linux family --------->
        if( $os == 'Unknown' ){
            $linux_os = array('Debian', 'Ubuntu', 'Kubuntu', 'Lynx', 'FreeBSD', 'Linux');
            $ci = count($linux_os);
            for( $i = 0; $i < $ci; $i++ ){
                if( strpos($useragent, $linux_os[$i]) !== false ){
                    $os = $linux_os[$i];
                    break;
                }
            }
        }
        // ---------------------------------------------------------------->
        
        
        // if we still don't have os, check if it's mac
        if( $os == 'Unknown' ){
            if(    strpos($useragent, 'Macintosh')   !== false
                || strpos($useragent, 'PowerPC')     !== false
                || strpos($useragent, 'Mac_PowerPC') !== false
            ) $os = 'Macintosh';
        }
        
        if( strpos($useragent, "OS/2") !== false ) $os = 'OS/2';
        if( strpos($useragent, "BeOS") !== false ) $os = 'BeOS';
        
        // WARNING, this line breaks login, because some os are more then 50 char
        // if uncomment add substr
        //if( $os == 'Unknown' ) $os = $useragent;
        
        // check arhitecure
        if( strpos($useragent, "WOW64") !== false ) $os .= ', x64';
        
        
        if( $os == 'Unknown' && i_browser_is_mobile() ){
            
            $os = 'Mobile';
            
            // check if symbian os
            if(    strpos($useragent, 'SymbianOS') !== false
                || strpos($useragent, 'SymbOS')    !== false  ){
                $os .= ', SymbianOS';
            }
            
            
            if( strpos($useragent, "Android")    !== false ) $os .= ', Android';
            if( strpos($useragent, "iPhone")     !== false ) $os .= ', iOS';
            if( strpos($useragent, "iPad")       !== false ) $os .= ', iOS';
            if( strpos($useragent, "BlackBerry") !== false ) $os .= ', BlackBerry';
        }
        
        return $os;
} // i_browser_os


// i_browser_id()
//   Get browser id
// parameters:
//   - 
// return:
//   id - int - id of browser (key of browser in $browsers array)
//   false - browser is not in $browsers array 
// notes: 
//   -
// dependencies:
//   -
// history:
//   24.03.2010 - SD - created
function i_browser_id(){
    
    $res = '';
    
    $agent = $_SERVER['HTTP_USER_AGENT'];
    $browsers = array(1 => 'msie',
                      2 => 'firefox',
                      3 => 'opera',
                      4 => 'chrome' //note: $_SERVER['HTTP_USER_AGENT'] for 'chrome' contains also name 'safari', but it is different browsers 
                );
    $return = false;
    
    foreach( $browsers as $browser_id => $browser ){
        if( preg_match('/'.$browser.'/i', $agent) === 1 ) {
            $res = $browser_id;
        }
    }
    
    return $res;
    
} // i_browser_id


// i_browser_ver()
//   get browser verion 
// parameters:
//   -
// return:
//   string  - empty or version
// notes: 
//   WARNING: gets version to first '.'
// dependencies:  
//   -
// history:
//   06.07.2011 - SD - created
function i_browser_ver(){
    
    $res = '';
    
    $browser_abbr =  i_browser_abbr();
    
    if( preg_match('/[0-9]+./i', $browser_abbr, $matches) === 1 ){
        $res = substr($matches[0], 0, strlen($matches[0])-1);
    }
    // print_pre($res);
     
    return $res;    
} // i_browser_ver

?>