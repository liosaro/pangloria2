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

mysql_select_db($database_basepangloria, $basepangloria);
$query_elimiProve = "SELECT * FROM CATPROVEEDOR WHERE ELIMIN=0 and EDITA =0 ORDER BY IDPROVEEDOR ASC";
$elimiProve = mysql_query($query_elimiProve, $basepangloria) or die(mysql_error());
$row_elimiProve = mysql_fetch_assoc($elimiProve);
$totalRows_elimiProve = mysql_num_rows($elimiProve);

$queryString_elimiProve = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_elimiProve") == false && 
        stristr($param, "totalRows_elimiProve") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_elimiProve = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_elimiProve = sprintf("&totalRows_elimiProve=%d%s", $totalRows_elimiProve, $queryString_elimiProve);
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
<table width="820" border="0">
  <tr></tr>
<tr>
  <td><iframe src="filtroeliminacionProve.php" name="conten" width="820" height="400" scrolling="auto" id="conten"></iframe>&nbsp;</td>
</tr>
<tr>
  <td>&nbsp;</td>
</tr>
<tr>
  <td><form action="filtroeliminacionProve.php" method="post" name="form1" target="conten" id="form1">
    <label for="filtroProve"></label>
    Ingrese el Nombre del Proveedor a Filtrar
    <input type="text" name="filtroProve" id="filtroProve" />
    <input type="submit" name="button" id="button" value="Filtrar" />
  </form></td>
</tr>
<tr>
  <td><table border="1">
    <tr class="retabla">
      <td colspan="7" align="center">&nbsp;</td>
      </tr>
    <tr class="retabla">
      <td align="center" bgcolor="#000000">Eliminar</td>
      <td align="center" bgcolor="#000000">Código</td>
      <td align="center" bgcolor="#000000">Nombre</td>
      <td align="center" bgcolor="#000000">Dirección </td>
      <td align="center" bgcolor="#000000">Teléfono </td>
      <td align="center" bgcolor="#000000">Correo Electrónico</td>
      <td align="center" bgcolor="#000000">Giro</td>
      </tr>
    <?php do { ?>
      <tr>
        <td><a href="javascript:;" onclick="aviso('eliminarpro.php?root=<?php echo $row_elimiProve['IDPROVEEDOR']; ?>'); return false;"><img src="../../../imagenes/icono/delete-32.png" width="32" height="32" /></a></td>
        <td><?php echo $row_elimiProve['IDPROVEEDOR']; ?></td>
        <td><?php echo $row_elimiProve['NOMBREPROVEEDOR']; ?></td>
        <td><?php echo $row_elimiProve['DIRECCIONPROVEEDOR']; ?></td>
        <td><?php echo $row_elimiProve['TELEFONOPROVEEDOR']; ?></td>
        <td><?php echo $row_elimiProve['CORREOPROVEEDOR']; ?></td>
        <td><?php echo $row_elimiProve['GIRO']; ?></td>
        </tr>
      <?php } while ($row_elimiProve = mysql_fetch_assoc($elimiProve)); ?>
    </table></td>
</tr>
</table>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($elimiProve);
?>
