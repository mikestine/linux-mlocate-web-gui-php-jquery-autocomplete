<?php
// Cool Interactive MLocate Search
// Mike Stine, 2017

setlocale(LC_CTYPE, "en_US.UTF-8");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('memory_limit', '1024M');
error_reporting(E_ALL);


// this function escapes strings that can contain malicious code
function clean($data){
  $data = escapeshellarg($data);
  return $data;
}

$query_original="";
$query_clean="";
$results="";
$row_count=0;

// get query param 
if(isset($_POST["query"])){
  $query_original= $_POST["query"];
}
else if(isset($_GET["query"])){
  $query_original= $_GET["query"];
}

// search if query is greater than 3 length
if ( strlen(trim($query_original))>3 ){
  
  // clean param  for linux shell execution
  $query_clean = clean($query_original);

  // execute shell command locate, and convert newlines to <br>
  $results=str_replace( "/mnt/storage", "",   nl2br(shell_exec('locate --regex  --ignore-case  --database /home/mlocate_dbs/storage.db '.$query_clean)) );

  //bold query in results
  $results= preg_replace("+".preg_quote($query_original)."+i", "<span class='matched'>$0</span>", $results);

  // count rows
  $row_count=substr_count( $results, "\n" );
}

echo "<span class='title'>RESULTS: </span>".$row_count."<BR><BR>".$results
?>
