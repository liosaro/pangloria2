<?php require_once('../../../../Connections/basepangloria.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

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
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "../../../../seguridad.php";
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO TRNENCABEZADOORDENPROD (IDENCABEORDPROD, IDEMPLEADO, IDSUCURSAL, FECHA) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($_POST['IDENCABEORDPROD'], "int"),
                       GetSQLValueString($_POST['IDEMPLEADO'], "int"),
                       GetSQLValueString($_POST['IDSUCURSAL'], "int"),
                       GetSQLValueString($_POST['FECHA'], "date"));

  mysql_select_db($database_basepangloria, $basepangloria);
  $Result1 = mysql_query($insertSQL, $basepangloria) or die(mysql_error());
}

mysql_select_db($database_basepangloria, $basepangloria);
$query_sucur = "SELECT IDSUCURSAL, NOMBRESUCURSAL FROM CATSUCURSAL ORDER BY IDSUCURSAL ASC";
$sucur = mysql_query($query_sucur, $basepangloria) or die(mysql_error());
$row_sucur = mysql_fetch_assoc($sucur);
$totalRows_sucur = mysql_num_rows($sucur);


mysql_select_db($database_basepangloria, $basepangloria);
$query_ultimoenca = "SELECT IDENCABEORDPROD FROM TRNENCABEZADOORDENPROD ORDER BY IDENCABEORDPROD DESC";
$ultimoenca = mysql_query($query_ultimoenca, $basepangloria) or die(mysql_error());
$row_ultimoenca = mysql_fetch_assoc($ultimoenca);
$totalRows_ultimoenca = mysql_num_rows($ultimoenca);

$colname_idusuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_idusuario = $_SESSION['MM_Username'];
}
mysql_select_db($database_basepangloria, $basepangloria);
$query_idusuario = sprintf("SELECT IDUSUARIO FROM CATUSUARIO WHERE NOMBREUSUARIO = %s", GetSQLValueString($colname_idusuario, "text"));
$idusuario = mysql_query($query_idusuario, $basepangloria) or die(mysql_error());
$row_idusuario = mysql_fetch_assoc($idusuario);
$totalRows_idusuario = mysql_num_rows($idusuario);

mysql_select_db($database_basepangloria, $basepangloria);
$nom = $row_idusuario['IDUSUARIO'];
$query_emplenomb = "SELECT NOMBREEMPLEADO FROM CATEMPLEADO WHERE IDUSUARIO = '$nom'";
$emplenomb = mysql_query($query_emplenomb, $basepangloria) or die(mysql_error());
$row_emplenomb = mysql_fetch_assoc($emplenomb);
$totalRows_emplenomb = mysql_num_rows($emplenomb);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<link href="../../../../css/forms.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table width="820" border="0">
  <tr>
    <td bgcolor="#999999" class="encaforms">Nuevo Encabezado de Orden de Producción</td>
  </tr>
  <tr>
    <td><form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
      <table align="center">
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">IDENCABEORDPROD:</td>
          <td><input name="IDENCABEORDPROD" type="text" value="" size="32" readonly="readonly" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">IDEMPLEADO:</td>
          <td><input name="IDEMPLEADO" type="text" value="<?php echo $row_idusuario['IDUSUARIO']; ?>" size="4" readonly="readonly" />
            <label for="textfield"></label>
            <input name="textfield" type="text" disabled="disabled" id="textfield" value="<?php echo $row_emplenomb['NOMBREEMPLEADO']; ?>" readonly="readonly" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">IDSUCURSAL:</td>
          <td><select name="IDSUCURSAL">
            <?php 
do {  
?>
            <option value="<?php echo $row_sucur['IDSUCURSAL']?>" ><?php echo $row_sucur['NOMBRESUCURSAL']?></option>
            <?php
} while ($row_sucur = mysql_fetch_assoc($sucur));
?>
          </select></td>
        </tr>
        <tr> </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">FECHA:</td>
          <td><input type="text" name="FECHA" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">&nbsp;</td>
          <td><input type="submit" value="Insertar registro" /></td>
        </tr>
      </table>
      <input type="hidden" name="MM_insert" value="form1" />
    </form></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($sucur);

mysql_free_result($empleado);

mysql_free_result($ultimoenca);

mysql_free_result($idusuario);

mysql_free_result($emplenomb);
?>
