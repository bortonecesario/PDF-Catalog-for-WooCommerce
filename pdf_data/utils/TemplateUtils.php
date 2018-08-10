<?php

function MakePlainText($text, $len = 0) {
    $text_trim = trim(strip_tags(html_entity_decode($text, ENT_QUOTES, 'UTF-8')));
    if ($len == 0) {
        return $text_trim;
    } else { 
        if (strlen($text_trim)>$len) {
            return utf8_substr($text_trim, 0, $len).'...';
        } else {
            return $text_trim;
        }
    }
}

function utf8_strrev($str) {
    return iconv("UTF-16LE", "UTF-8", strrev(iconv("UTF-8", "UTF-16BE", $str)));
}

?>    