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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO TRNCABEZACOTIZACION (IDENCABEZADO, IDVENDEDOR, IDPROVEEDOR, IDEMPLEADO, IDCONDICION, FECHACOTIZACION, VALIDEZOFERTA, PLAZOENTREGA) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['IDENCABEZADO'], "int"),
                       GetSQLValueString($_POST['IDVENDEDOR'], "int"),
                       GetSQLValueString($_POST['IDPROVEEDOR'], "int"),
                       GetSQLValueString($_POST['IDEMPLEADO'], "int"),
                       GetSQLValueString($_POST['IDCONDICION'], "int"),
                       GetSQLValueString($_POST['FECHACOTIZACION'], "date"),
                       GetSQLValueString($_POST['VALIDEZOFERTA'], "int"),
                       GetSQLValueString($_POST['PLAZOENTREGA'], "int"));

  mysql_select_db($database_basepangloria, $basepangloria);
  $Result1 = mysql_query($insertSQL, $basepangloria) or die(mysql_error());
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
$query_empleao = "SELECT IDEMPLEADO, NOMBREEMPLEADO FROM CATEMPLEADO";
$empleao = mysql_query($query_empleao, $basepangloria) or die(mysql_error());
$row_empleao = mysql_fetch_assoc($empleao);
$totalRows_empleao = mysql_num_rows($empleao);

mysql_select_db($database_basepangloria, $basepangloria);
$query_proveedor = "SELECT IDPROVEEDOR, NOMBREPROVEEDOR FROM CATPROVEEDOR WHERE ELIMIN = 0";
$proveedor = mysql_query($query_proveedor, $basepangloria) or die(mysql_error());
$row_proveedor = mysql_fetch_assoc($proveedor);
$totalRows_proveedor = mysql_num_rows($proveedor);

mysql_select_db($database_basepangloria, $basepangloria);
$query_VendedorProveedor = "SELECT IDVENDEDOR, NOM FROM CATVENDEDOR_PROV";
$VendedorProveedor = mysql_query($query_VendedorProveedor, $basepangloria) or die(mysql_error());
$row_VendedorProveedor = mysql_fetch_assoc($VendedorProveedor);
$totalRows_VendedorProveedor = mysql_num_rows($VendedorProveedor);

mysql_select_db($database_basepangloria, $basepangloria);
$query_condicion = "SELECT IDCONDICION, TIPO FROM CATCONDICIONPAGO WHERE ELIMIN = 0";
$condicion = mysql_query($query_condicion, $basepangloria) or die(mysql_error());
$row_condicion = mysql_fetch_assoc($condicion);
$totalRows_condicion = mysql_num_rows($condicion);

mysql_select_db($database_basepangloria, $basepangloria);
$query_Recordset1 = "SELECT IDENCABEZADO FROM TRNCABEZACOTIZACION ORDER BY IDENCABEZADO DESC";
$Recordset1 = mysql_query($query_Recordset1, $basepangloria) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);
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
}
</style>
<link href="../../../css/forms.css" rel="stylesheet" type="text/css" />
<script src="../../../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
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
<link href="../../../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form action="<?php echo $editFormAction; ?>" method="POST" name="form1" id="form1">
  <table align="left">
    <tr valign="baseline">
      <td colspan="6" align="center" nowrap="nowrap" class="warning"><span class="encaforms">Ingreso Nueva Cotizacion</span></td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">No. de Cotizacion:</td>
      <td><input name="IDENCABEZADO" type="text" disabled="disabled" value="<?php echo $row_Recordset1['IDENCABEZADO']+1; ?>" size="9" readonly="readonly" /></td>
      <td>Proveedor:</td>
      <td><select name="IDPROVEEDOR" onchange="document.form1.subit.disabled=false">
        <?php 
do {  
?>
        <option value="<?php echo $row_proveedor['IDPROVEEDOR']?>" ><?php echo $row_proveedor['NOMBREPROVEEDOR']?></option>
        <?php
} while ($row_proveedor = mysql_fetch_assoc($proveedor));
?>
      </select></td>
      <td>Vendedor de Proveedor:</td>
      <td><select name="IDVENDEDOR" document.form1.subit.disabled=false>
        <?php 
do {  
?>
        <option value="<?php echo $row_VendedorProveedor['IDVENDEDOR']?>" ><?php echo $row_VendedorProveedor['NOM']?></option>
        <?php
} while ($row_VendedorProveedor = mysql_fetch_assoc($VendedorProveedor));
?>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Condicion de Pago:</td>
      <td><select name="IDCONDICION">
        <?php
do {  
?>
        <option value="<?php echo $row_condicion['IDCONDICION']?>"><?php echo $row_condicion['TIPO']?></option>
        <?php
} while ($row_condicion = mysql_fetch_assoc($condicion));
  $rows = mysql_num_rows($condicion);
  if($rows > 0) {
      mysql_data_seek($condicion, 0);
	  $row_condicion = mysql_fetch_assoc($condicion);
  }
?>
      </select></td>
      <td>Empleado:</td>
      <td><input name="IDEMPLEADO" type="text" value="<?php echo $row_empleao['IDEMPLEADO']; ?>" size="5" readonly="readonly" />
      <?php echo $row_empleao['NOMBREEMPLEADO']; ?></td>
      <td>Fecha de Cotizacion:</td>
      <td><input type="text" name="FECHACOTIZACION" value="<?php echo date("Y-m-d") ?>" size="15" readonly="readonly" /></td>
    </tr>
    <tr> </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Valides de Oferta:</td>
      <td><span id="sprytextfield1">
      <input type="text" name="VALIDEZOFERTA" value="" size="9" />
      <span class="textfieldRequiredMsg">Se necesita un valor.</span><span class="textfieldInvalidFormatMsg">Formato no válido.</span><span class="textfieldMinValueMsg">El valor introducido es inferior al mínimo permitido.</span></span></td>
      <td>Plazo de entrega:</td>
      <td><span id="sprytextfield2">
      <input type="text" name="PLAZOENTREGA" value="" size="9" />
      <span class="textfieldRequiredMsg">Se necesita un valor.</span><span class="textfieldInvalidFormatMsg">Formato no válido.</span><span class="textfieldMinValueMsg">El valor introducido es inferior al mínimo permitido.</span></span></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td>&nbsp;</td>
      <td><input type="submit" value="Insertar registro"  name="subit" id="subit" disabled="disabled" onclick="Confirm(this.form)" /></td>
      <td><input  class="label-important" type="submit" name="button" id="button" value="Cerrar" onclick="cerrarse()" /></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form1" />
</form>
<p>&nbsp;</p>
<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "integer", {validateOn:["blur"], minValue:0});
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "integer", {minValue:0, validateOn:["blur"]});
</script>
</body>
</html>
<?php
mysql_free_result($ncoti);

mysql_free_result($numorden);

mysql_free_result($nusuario);

mysql_free_result($empleao);

mysql_free_result($proveedor);

mysql_free_result($VendedorProveedor);

mysql_free_result($condicion);

mysql_free_result($Recordset1);
?>
