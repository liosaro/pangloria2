<?php require_once('../../../Connections/basepangloria.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}
mysql_select_db($database_basepangloria, $basepangloria);

mysql_query("Update CATMATERIAPRIMA set CantDisponible=$nueva where IDMATPRIMA= $mat");
$id=$_GET["root"];
$mat=$_GET["mat"];
$canti= $_GET["canti"];
$sql2="Select CantDisponible from CATMATERIAPRIMA where IDMATPRIMA= $mat ";
			   $rsl2=mysql_query($sql2);
			   $fil2 = mysql_fetch_array($rsl2);
			   $nueva= $fil2['CantDisponible'] - $canti;
			   mysql_query("Update CATMATERIAPRIMA set CantDisponible=$nueva where IDMATPRIMA= $mat");
$query = "UPDATE TRNENTRADA_INVENTARIO SET ELIMIN=1 WHERE IDENTRADA=$id";
    $result = mysql_query($query);

    if (!$result) {
        echo "No pudo ejecutarse satisfactoriamente la consulta ($query) " .
        "en la BD: " . mysql_error();
        //Finalizo la aplicación
        exit;
    }
function urlActual() {
 $pageURL = 'http://';
 if ($_SERVER["SERVER_PORT"] != "80") {
 $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
 $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
 }
$url = $_SERVER['HTTP_REFERER'];
echo $url;
header ("location: $url ");
?>
