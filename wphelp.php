<?php 
session_start(); 

function download_page($path){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$path);
        curl_setopt($ch, CURLOPT_FAILONERROR,1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $retValue = curl_exec($ch);                      
        curl_close($ch);
        //echo $retValue;
        return $retValue;
}

# LOAD XML FILE
$doc = 'https://blogs.princeton.edu/research-account/category/' . $_GET['tag'] . '/feed/';
$XML = new DOMDocument();	
$XML->loadXML( download_page($doc) );

# START XSLT
$xslt = new XSLTProcessor();
$XSL = new DOMDocument();
$XSL->load( 'wphelp.xsl');
$xslt->importStylesheet( $XSL );

// Collect thumbnail paths and pass in as params
// $xslt->setParameter('', 'search_params', $_SESSION['current_search']);
#PRINT
print $xslt->transformToXML( $XML );
  
?>
