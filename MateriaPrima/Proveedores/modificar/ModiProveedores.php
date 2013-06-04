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

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_modiProvee = 10;
$pageNum_modiProvee = 0;
if (isset($_GET['pageNum_modiProvee'])) {
  $pageNum_modiProvee = $_GET['pageNum_modiProvee'];
}
$startRow_modiProvee = $pageNum_modiProvee * $maxRows_modiProvee;

mysql_select_db($database_basepangloria, $basepangloria);
$query_modiProvee = "SELECT * FROM CATPROVEEDOR WHERE ELIMIN = '0' ORDER BY IDPROVEEDOR DESC";
$query_limit_modiProvee = sprintf("%s LIMIT %d, %d", $query_modiProvee, $startRow_modiProvee, $maxRows_modiProvee);
$modiProvee = mysql_query($query_limit_modiProvee, $basepangloria) or die(mysql_error());
$row_modiProvee = mysql_fetch_assoc($modiProvee);

if (isset($_GET['totalRows_modiProvee'])) {
  $totalRows_modiProvee = $_GET['totalRows_modiProvee'];
} else {
  $all_modiProvee = mysql_query($query_modiProvee);
  $totalRows_modiProvee = mysql_num_rows($all_modiProvee);
}
$totalPages_modiProvee = ceil($totalRows_modiProvee/$maxRows_modiProvee)-1;$maxRows_modiProvee = 10;
$pageNum_modiProvee = 0;
if (isset($_GET['pageNum_modiProvee'])) {
  $pageNum_modiProvee = $_GET['pageNum_modiProvee'];
}
$startRow_modiProvee = $pageNum_modiProvee * $maxRows_modiProvee;

mysql_select_db($database_basepangloria, $basepangloria);
$query_modiProvee = "SELECT * FROM CATPROVEEDOR ORDER BY IDPROVEEDOR DESC";
$query_limit_modiProvee = sprintf("%s LIMIT %d, %d", $query_modiProvee, $startRow_modiProvee, $maxRows_modiProvee);
$modiProvee = mysql_query($query_limit_modiProvee, $basepangloria) or die(mysql_error());
$row_modiProvee = mysql_fetch_assoc($modiProvee);

if (isset($_GET['totalRows_modiProvee'])) {
  $totalRows_modiProvee = $_GET['totalRows_modiProvee'];
} else {
  $all_modiProvee = mysql_query($query_modiProvee);
  $totalRows_modiProvee = mysql_num_rows($all_modiProvee);
}
$totalPages_modiProvee = ceil($totalRows_modiProvee/$maxRows_modiProvee)-1;

$queryString_modiProvee = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_modiProvee") == false && 
        stristr($param, "totalRows_modiProvee") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_modiProvee = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_modiProvee = sprintf("&totalRows_modiProvee=%d%s", $totalRows_modiProvee, $queryString_modiProvee);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<style type="text/css">
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
</style>
<link href="../../../css/forms.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table border="1">
  <tr>
    <td colspan="5"><iframe src="modificarProveedor.php" name="modificar" width="850" height="400" align="middle" scrolling="NO" frameborder="0" id="modificar "></iframe></td>
  </tr>
  <tr>
    <td colspan="5"><form action="filtroProveedor.php" method="post" name="form2" target="modificar" id="form2">
      Ingrese el Nombre
      del Proveedor a Modificar:
      <input type="text" name="FiltroProvee" id="FiltroProvee" />
      <input type="submit" name="btnfiltrar" id="btnfiltrar" value="Filtro" />
    </form></td>
  </tr>
  <tr>
    <td colspan="5">&nbsp;</td>
  </tr>
  <tr class="retabla">
    <td align="center" bgcolor="#000000">Modificar</td>
    <td align="center" bgcolor="#000000">Código de Proveedor</td>
    <td align="center" bgcolor="#000000">Nombre de Proveedor</td>
    <td align="center" bgcolor="#000000">Giro</td>
    <td align="center" bgcolor="#000000">Numero de Registro</td>
  </tr>
  <?php do { ?>
    <tr>
      <td><a href="modificarProveedor.php?root=<?php echo $row_modiProvee['IDPROVEEDOR']; ?>"target="modificar"><img src="../../../imagenes/icono/modi.png" width="32" height="32" /></a></td>
      <td><?php echo $row_modiProvee['IDPROVEEDOR']; ?></td>
      <td><?php echo $row_modiProvee['NOMBREPROVEEDOR']; ?></td>
      <td><?php echo $row_modiProvee['GIRO']; ?></td>
      <td><?php echo $row_modiProvee['NUMEROREGISTRO']; ?></td>
    </tr>
    <?php } while ($row_modiProvee = mysql_fetch_assoc($modiProvee)); ?>
</table>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($modiProvee);
?>
