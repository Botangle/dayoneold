<?php

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/* SETTINGS START */

$keywords = 'adult, naughty, 18+, dating, hot, sex,rango, interested,';
$partner_id = '53f6b8a7c5e84444';

/* SETTINGS END */

$keywordlist = '';

if (!empty($keywords)) {
    $keywordsarray = explode(',', $keywords);
    foreach ($keywordsarray as $keyword) {
        if ($keyword) {
            $keyword = trim($keyword);
            $keyword = preg_replace('/[^\w\d_ -]/si', '', $keyword);
            $keywordlist .= '|' . $keyword . '';
        }
    }
    $keywordlist = substr($keywordlist, 1);
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
