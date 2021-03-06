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
?>
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

$maxRows_filtroPro = 10;
$pageNum_filtroPro = 0;
if (isset($_GET['pageNum_filtroPro'])) {
  $pageNum_filtroPro = $_GET['pageNum_filtroPro'];
}
$startRow_filtroPro = $pageNum_filtroPro * $maxRows_filtroPro;

$colname_filtroPro = "-1";
if (isset($_POST['filtroProve'])) {
  $colname_filtroPro = $_POST['filtroProve'];
}
mysql_select_db($database_basepangloria, $basepangloria);
$query_filtroPro = sprintf("SELECT * FROM CATPROVEEDOR WHERE NOMBREPROVEEDOR LIKE %s AND ELIMIN=0 ORDER BY IDPROVEEDOR ASC", GetSQLValueString("%" . $colname_filtroPro . "%", "text"));
$query_limit_filtroPro = sprintf("%s LIMIT %d, %d", $query_filtroPro, $startRow_filtroPro, $maxRows_filtroPro);
$filtroPro = mysql_query($query_limit_filtroPro, $basepangloria) or die(mysql_error());
$row_filtroPro = mysql_fetch_assoc($filtroPro);

if (isset($_GET['totalRows_filtroPro'])) {
  $totalRows_filtroPro = $_GET['totalRows_filtroPro'];
} else {
  $all_filtroPro = mysql_query($query_filtroPro);
  $totalRows_filtroPro = mysql_num_rows($all_filtroPro);
}
$totalPages_filtroPro = ceil($totalRows_filtroPro/$maxRows_filtroPro)-1;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<script language="JavaScript">
function aviso(url){
if (!confirm("ALERTA!! va a proceder a eliminar este registro, si desea eliminarlo de click en ACEPTAR\n de lo contrario de click en CANCELAR.")) {
return false;
}
else {
document.location = url;
return true;
}
}
</script>
<link href="../../../css/forms.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table border="1">
  <tr class="retabla">
    <td align="center" bgcolor="#000000">Eliminación</td>
    <td align="center" bgcolor="#000000">Código</td>
    <td align="center" bgcolor="#000000">Proveedor</td>
    <td align="center" bgcolor="#000000">Dirección</td>
    <td align="center" bgcolor="#000000">Tele fono</td>
    <td align="center" bgcolor="#000000">Correo Electrónico</td>
    <td align="center" bgcolor="#000000">Giro</td>
  </tr>
  <?php do { ?>
    <tr>
      <td><a href="javascript:;" onclick="aviso('eliminarpro.php?root=<?php echo $row_filtroPro['IDPROVEEDOR']; ?>'); return false;"><img src="../../../imagenes/icono/delete-32.png" width="32" height="32" /></a></td>
      <td><?php echo $row_filtroPro['IDPROVEEDOR']; ?></td>
      <td><?php echo $row_filtroPro['NOMBREPROVEEDOR']; ?></td>
      <td><?php echo $row_filtroPro['DIRECCIONPROVEEDOR']; ?></td>
      <td><?php echo $row_filtroPro['TELEFONOPROVEEDOR']; ?></td>
      <td><?php echo $row_filtroPro['CORREOPROVEEDOR']; ?></td>
      <td><?php echo $row_filtroPro['GIRO']; ?></td>
    </tr>
    <?php } while ($row_filtroPro = mysql_fetch_assoc($filtroPro)); ?>
</table>
</body>
</html>
<?php
mysql_free_result($filtroPro);
?>
