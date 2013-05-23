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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO TRNENCABEZADOSALIDMATPRIMA (IDENCABEZADOSALMATPRI, IDEMPLEADO, ID_PED_MAT_PRIMA, FECHAYHORASALIDAMATPRIMA, USUARIO) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['IDENCABEZADOSALMATPRI'], "int"),
                       GetSQLValueString($_POST['IDEMPLEADO'], "int"),
                       GetSQLValueString($_POST['ID_PED_MAT_PRIMA'], "int"),
                       GetSQLValueString($_POST['FECHAYHORASALIDAMATPRIMA'], "date"),
                       GetSQLValueString($_POST['USUARIO'], "int"));

  mysql_select_db($database_basepangloria, $basepangloria);
  $Result1 = mysql_query($insertSQL, $basepangloria) or die(mysql_error());
}

mysql_select_db($database_basepangloria, $basepangloria);
$query_ultimoenca = "SELECT ID_ENCAPEDIDO FROM TRNENCABEZADOPEDMATPRI ORDER BY ID_ENCAPEDIDO DESC";
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
$query_emplia = "SELECT IDEMPLEADO, NOMBREEMPLEADO FROM CATEMPLEADO ORDER BY NOMBREEMPLEADO ASC";
$emplia = mysql_query($query_emplia, $basepangloria) or die(mysql_error());
$row_emplia = mysql_fetch_assoc($emplia);
$totalRows_emplia = mysql_num_rows($emplia);

mysql_select_db($database_basepangloria, $basepangloria);
$query_ultimatpri = "SELECT IDENCABEZADOSALMATPRI FROM TRNENCABEZADOSALIDMATPRIMA ORDER BY IDENCABEZADOSALMATPRI DESC";
$ultimatpri = mysql_query($query_ultimatpri, $basepangloria) or die(mysql_error());
$row_ultimatpri = mysql_fetch_assoc($ultimatpri);
$totalRows_ultimatpri = mysql_num_rows($ultimatpri);

mysql_select_db($database_basepangloria, $basepangloria);
$nom2 = $row_idusuario['IDUSUARIO'];
$query_idemple = "SELECT IDEMPLEADO FROM CATEMPLEADO WHERE IDUSUARIO = '$nom2'";
$idemple = mysql_query($query_idemple, $basepangloria) or die(mysql_error());
$row_idemple = mysql_fetch_assoc($idemple);
$totalRows_idemple = mysql_num_rows($idemple);

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
    
<script>
function cerrarse()
{
 opener.location.reload();
 window.close()
}
</script>
<link href="../../../../css/forms.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table width="820" border="0">
  <tr>
    <td bgcolor="#999999" class="encaforms">Nuevo Encabezado de Orden de Producción</td>
  </tr>
  <tr>
    <td>&nbsp;
      <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
      <table align="center">
          <tr valign="baseline">
            <td nowrap="nowrap" align="left">Salida de Materia Prima No.:</td>
            <td nowrap="nowrap" align="left"><input name="IDENCABEZADOSALMATPRI" type="text" disabled="disabled" value="<?php echo $row_ultimatpri['IDENCABEZADOSALMATPRI']+1; ?>" size="32" readonly="readonly" /></td>
            <td nowrap="nowrap" align="left">Pedido De Materia Prima No.:</td>
            <td align="left"><select name="IDEMPLEADO" onchange="document.form1.inse.disabled=false;">
              <?php
do {  
?>
              <option value="<?php echo $row_ultimoenca['ID_ENCAPEDIDO']?>"><?php echo $row_ultimoenca['ID_ENCAPEDIDO']?></option>
              <?php
} while ($row_ultimoenca = mysql_fetch_assoc($ultimoenca));
  $rows = mysql_num_rows($ultimoenca);
  if($rows > 0) {
      mysql_data_seek($ultimoenca, 0);
	  $row_ultimoenca = mysql_fetch_assoc($ultimoenca);
  }
?>
            </select></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="left">Empleado que pide la Materia Prima:</td>
            <td nowrap="nowrap" align="left"><select name="ID_PED_MAT_PRIMA" onchange="document.form1.inse.disabled=false;">
              <?php
do {  
?>
              <option value="<?php echo $row_emplia['IDEMPLEADO']?>"><?php echo $row_emplia['NOMBREEMPLEADO']?></option>
              <?php
} while ($row_emplia = mysql_fetch_assoc($emplia));
  $rows = mysql_num_rows($emplia);
  if($rows > 0) {
      mysql_data_seek($emplia, 0);
	  $row_emplia = mysql_fetch_assoc($emplia);
  }
?>
            </select></td>
            <td nowrap="nowrap" align="left">Fecha Y Hora de la Salida:</td>
            <td align="left"><input type="text" name="FECHAYHORASALIDAMATPRIMA" value="<?php echo date("Y-m-d H:i:s"); ?>" size="32" /></td>
          </tr>
          <tr> </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">&nbsp;</td>
            <td nowrap="nowrap" align="right">&nbsp;</td>
            <td nowrap="nowrap" align="right">&nbsp;</td>
            <td><input name="inse" type="submit" disabled id="inse" value="Insertar registro" /></td>
          </tr>
          <tr> </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">&nbsp;</td>
            <td nowrap="nowrap" align="right">&nbsp;</td>
            <td nowrap="nowrap" align="right">&nbsp;</td>
            <td><input type="button" name="cer" id="cer" value="cerrar" onclick="cerrarse();" /></td>
          </tr>
        </table>
        <input type="hidden" name="USUARIO" value="<?php echo $row_idusuario['IDUSUARIO']; ?>" />
        <input type="hidden" name="MM_insert" value="form1" />
      </form>
    <p>&nbsp;</p></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($empleado);

mysql_free_result($ultimoenca);

mysql_free_result($idusuario);

mysql_free_result($emplia);

mysql_free_result($ultimatpri);

mysql_free_result($idemple);

mysql_free_result($emplenomb);
?>
