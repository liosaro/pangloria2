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
  $insertSQL = sprintf("INSERT INTO TRNENCABEZADOCOMPRA (ID_DETENCCOM, IDPROVEEDOR, IDORDEN, IDEMPLEADO, ID_TIPO_FACTURA, IDESTAFACTURA, NOFACTURA, FECHACOMPRA) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['ID_DETENCCOM'], "int"),
                       GetSQLValueString($_POST['IDPROVEEDOR'], "int"),
                       GetSQLValueString($_POST['IDORDEN'], "int"),
                       GetSQLValueString($_POST['IDEMPLEADO'], "int"),
                       GetSQLValueString($_POST['ID_TIPO_FACTURA'], "int"),
                       GetSQLValueString($_POST['IDESTAFACTURA'], "int"),
                       GetSQLValueString($_POST['NOFACTURA'], "text"),
                       GetSQLValueString($_POST['FECHACOMPRA'], "date"));

  mysql_select_db($database_basepangloria, $basepangloria);
  $Result1 = mysql_query($insertSQL, $basepangloria) or die(mysql_error());
}



$colname_nusuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_nusuario = $_SESSION['MM_Username'];
}
mysql_select_db($database_basepangloria, $basepangloria);
$query_nusuario = sprintf("SELECT IDUSUARIO FROM CATUSUARIO WHERE NOMBREUSUARIO = %s", GetSQLValueString($colname_nusuario, "text"));
$nusuario = mysql_query($query_nusuario, $basepangloria) or die(mysql_error());
$row_nusuario = mysql_fetch_assoc($nusuario);
$totalRows_nusuario = mysql_num_rows($nusuario);

mysql_select_db($database_basepangloria, $basepangloria);
$query_Proveedor = "SELECT IDPROVEEDOR, NOMBREPROVEEDOR FROM CATPROVEEDOR WHERE ELIMIN = 0 ORDER BY NOMBREPROVEEDOR ASC";
$Proveedor = mysql_query($query_Proveedor, $basepangloria) or die(mysql_error());
$row_Proveedor = mysql_fetch_assoc($Proveedor);
$totalRows_Proveedor = mysql_num_rows($Proveedor);

mysql_select_db($database_basepangloria, $basepangloria);
$query_estadofactura = "SELECT IDESTAFACTURA, ESTADO FROM CATESTADOFACTURA WHERE ELIMIN = 0 ORDER BY ESTADO ASC";
$estadofactura = mysql_query($query_estadofactura, $basepangloria) or die(mysql_error());
$row_estadofactura = mysql_fetch_assoc($estadofactura);
$totalRows_estadofactura = mysql_num_rows($estadofactura);

mysql_select_db($database_basepangloria, $basepangloria);
$query_TipoFactura = "SELECT ID_TIPO_FACTURA, TIPOFACTURA FROM CATTIPOFACTURA WHERE ELIMIN = 0 ORDER BY TIPOFACTURA ASC";
$TipoFactura = mysql_query($query_TipoFactura, $basepangloria) or die(mysql_error());
$row_TipoFactura = mysql_fetch_assoc($TipoFactura);
$totalRows_TipoFactura = mysql_num_rows($TipoFactura);

mysql_select_db($database_basepangloria, $basepangloria);
$query_ordencompra = "SELECT IDORDEN FROM TRNENCAORDCOMPRA WHERE ELIMIN = 0 ORDER BY IDORDEN DESC";
$ordencompra = mysql_query($query_ordencompra, $basepangloria) or die(mysql_error());
$row_ordencompra = mysql_fetch_assoc($ordencompra);
$totalRows_ordencompra = mysql_num_rows($ordencompra);


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
<script>
function Confirm(form){

alert("Se ha agregado un nuevo registro!"); 

form.submit();
}

</script>
<script>
function validar(date)
  {
        var today = new Date();
        var date2= new Date(date);
 
        if (date2<today)
        {
            alert("Ha insertado Una Fecha Inferior a la del sistema es esto Correcto?"); 
        }
   }
  </script>
<link href="../../../../css/forms.css" rel="stylesheet" type="text/css" />

</head>

<body>
<table width="820" border="0">
  <tr>
    <td align="left">
      <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
      <table align="left">
          <tr valign="baseline">
            <td colspan="6" align="right" nowrap="nowrap" bgcolor="#999999" class="encaforms">Nueva Compra            </td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">Proveedor:</td>
            <td><select name="IDPROVEEDOR" onchange="document.form1.subit.disabled=false;">
              <?php 
do {  
?>
              <option value="<?php echo $row_Proveedor['IDPROVEEDOR']?>" ><?php echo $row_Proveedor['NOMBREPROVEEDOR']?></option>
              <?php
} while ($row_Proveedor = mysql_fetch_assoc($Proveedor));
?>
            </select></td>
            <td>No. de Orden:</td>
            <td><select name="IDORDEN" id="IDORDEN" onchange="document.form1.subit.disabled=false;">
              <?php 
do {  
?>
              <option value="<?php echo $row_ordencompra['IDORDEN']?>" ><?php echo $row_ordencompra['IDORDEN']?></option>
              <?php
} while ($row_ordencompra = mysql_fetch_assoc($ordencompra));
?>
            </select></td>
            <td>Tipo de Factura:</td>
            <td><select name="ID_TIPO_FACTURA" onchange="document.form1.subit.disabled=false;"> 
              <?php 
do {  
?>
              <option value="<?php echo $row_TipoFactura['ID_TIPO_FACTURA']?>" ><?php echo $row_TipoFactura['TIPOFACTURA']?></option>
              <?php
} while ($row_TipoFactura = mysql_fetch_assoc($TipoFactura));
?>
            </select></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">Estado de Factura:</td>
            <td><select name="IDESTAFACTURA" onchange="document.form1.subit.disabled=false;">
              <?php 
do {  
?>
              <option value="<?php echo $row_estadofactura['IDESTAFACTURA']?>" ><?php echo $row_estadofactura['ESTADO']?></option>
              <?php
} while ($row_estadofactura = mysql_fetch_assoc($estadofactura));
?>
            </select></td>
            <td>No. de Factura:</td>
            <td><input type="text" name="NOFACTURA" value="" size="10" /></td>
            <td>Código de Empleado que Ingresa</td>
            <td><input name="IDEMPLEADO" type="text" value="<?php echo $row_nusuario['IDUSUARIO']; ?>" size="4" readonly="readonly" /></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>Fecha de la Compra:</td>
            <td><input name="FECHACOMPRA" type="text" value="<?php echo date("Y-d-m")?>" size="15" readonly="readonly" /></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td><input type="submit" value="Insertar registro"  name="subit" id="subit" disabled="disabled" onclick="Confirm(this.form)" /></td>
            <td><input  class="label-important" type="submit" name="button" id="button" value="Cerrar" onclick="cerrarse()" /></td>
          </tr>
        </table>
        <input type="hidden" name="ID_DETENCCOM" value="" />
        <input type="hidden" name="MM_insert" value="form1" />
      </form>
    <p>&nbsp;</p></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($nusuario);

mysql_free_result($Proveedor);

mysql_free_result($estadofactura);

mysql_free_result($TipoFactura);

mysql_free_result($ordencompra);
?>
