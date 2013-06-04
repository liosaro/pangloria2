  <?php require_once('../../../Connections/basepangloria.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "37,39";
$MM_donotCheckaccess = "false";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && false) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "../../../seguridad.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
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

$maxRows_FiltroProveedor = 10;
$pageNum_FiltroProveedor = 0;
if (isset($_GET['pageNum_FiltroProveedor'])) {
  $pageNum_FiltroProveedor = $_GET['pageNum_FiltroProveedor'];
}
$startRow_FiltroProveedor = $pageNum_FiltroProveedor * $maxRows_FiltroProveedor;

$colname_FiltroProveedor = "-1";
if (isset($_POST['FiltroProvee'])) {
  $colname_FiltroProveedor = $_POST['FiltroProvee'];
}
mysql_select_db($database_basepangloria, $basepangloria);
$query_FiltroProveedor = sprintf("SELECT * FROM CATPROVEEDOR WHERE NOMBREPROVEEDOR LIKE %s AND ELIMIN=0  ORDER BY NOMBREPROVEEDOR ASC", GetSQLValueString("%" . $colname_FiltroProveedor . "%", "text"));
$query_limit_FiltroProveedor = sprintf("%s LIMIT %d, %d", $query_FiltroProveedor, $startRow_FiltroProveedor, $maxRows_FiltroProveedor);
$FiltroProveedor = mysql_query($query_limit_FiltroProveedor, $basepangloria) or die(mysql_error());
$row_FiltroProveedor = mysql_fetch_assoc($FiltroProveedor);

if (isset($_GET['totalRows_FiltroProveedor'])) {
  $totalRows_FiltroProveedor = $_GET['totalRows_FiltroProveedor'];
} else {
  $all_FiltroProveedor = mysql_query($query_FiltroProveedor);
  $totalRows_FiltroProveedor = mysql_num_rows($all_FiltroProveedor);
}
$totalPages_FiltroProveedor = ceil($totalRows_FiltroProveedor/$maxRows_FiltroProveedor)-1;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<link href="../../../css/forms.css" rel="stylesheet" type="text/css" />
</head>
<body>
<table border="1">
  <tr class="retabla">
    <td align="center" bgcolor="#000000">Modificar</td>
    <td align="center" bgcolor="#000000">Código de Proveedor</td>
    <td align="center" bgcolor="#000000">Nombre de Proveedor</td>
    <td align="center" bgcolor="#000000">Giro</td>
    <td align="center" bgcolor="#000000">Numero de Registro</td>
  </tr>
  <?php do { ?>
  <tr>
    <td><a href="modificarProveedor.php?root=<?php echo $row_FiltroProveedor['IDPROVEEDOR']; ?>"target="modificar"><img src="../../../imagenes/icono/modi.png" alt="" width="32" height="32" /></a></td>
    <td><?php echo $row_FiltroProveedor['IDPROVEEDOR']; ?></td>
    <td><?php echo $row_FiltroProveedor['NOMBREPROVEEDOR']; ?></td>
    <td><?php echo $row_FiltroProveedor['GIRO']; ?></td>
    <td><?php echo $row_FiltroProveedor['NUMEROREGISTRO']; ?></td>
  </tr>
  <?php } while ($row_FiltroProveedor = mysql_fetch_assoc($FiltroProveedor)); ?>
</table>
<iframe src="modificarProveedor.php" name="modificar2" width="850" height="400" scrolling="auto"></iframe>

</body>
</html>
<?php


mysql_free_result($FiltroProveedor);
?>

