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
  $insertSQL = sprintf("INSERT INTO TRNDEVOLUCIONCOMPRA (IDDEVOLUCION, IDEMPLEADO, ID_DETENCCOM, DOCADEVOLVER, FECHADEVOLUCION, IMPORTE, GASTOGENERADO, OBSERVACION) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['IDDEVOLUCION'], "int"),
                       GetSQLValueString($_POST['IDEMPLEADO'], "int"),
                       GetSQLValueString($_POST['ID_DETENCCOM'], "int"),
                       GetSQLValueString($_POST['DOCADEVOLVER'], "text"),
                       GetSQLValueString($_POST['FECHADEVOLUCION'], "date"),
                       GetSQLValueString($_POST['IMPORTE'], "double"),
                       GetSQLValueString($_POST['GASTOGENERADO'], "double"),
                       GetSQLValueString($_POST['OBSERVACION'], "text"));

  mysql_select_db($database_basepangloria, $basepangloria);
  $Result1 = mysql_query($insertSQL, $basepangloria) or die(mysql_error());
  
}
// CONSULTA PARA LLENAR EL COMBO DE COMPRAS
mysql_select_db($database_basepangloria, $basepangloria);
$query_compra = "SELECT ID_DETENCCOM FROM TRNENCABEZADOCOMPRA WHERE ELIMIN = 0 ORDER BY ID_DETENCCOM DESC";
$compra = mysql_query($query_compra, $basepangloria) or die(mysql_error());
$row_compra = mysql_fetch_assoc($compra);
$totalRows_compra = mysql_num_rows($compra);
// CONSULTA PARA OBTENER EL ID DE USUARIO
$colname_Recordset1 = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_Recordset1 = $_SESSION['MM_Username'];
}
mysql_select_db($database_basepangloria, $basepangloria);
$query_Recordset1 = sprintf("SELECT IDUSUARIO FROM CATUSUARIO WHERE NOMBREUSUARIO = %s", GetSQLValueString($colname_Recordset1, "text"));
$Recordset1 = mysql_query($query_Recordset1, $basepangloria) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);
// CONSULTA PARA OBTENER EL NOMBRE DEL EMPLEADO EN BASE A EL ID DEL USUARIO LOGEADO
$IDUSUARIO= $row_Recordset1['IDUSUARIO'];
mysql_select_db($database_basepangloria, $basepangloria);
$query_nombreempleado = "SELECT NOMBREEMPLEADO FROM CATEMPLEADO WHERE IDUSUARIO = $IDUSUARIO";
$nombreempleado = mysql_query($query_nombreempleado, $basepangloria) or die(mysql_error());
$row_nombreempleado = mysql_fetch_assoc($nombreempleado);
$totalRows_nombreempleado = mysql_num_rows($nombreempleado);

mysql_select_db($database_basepangloria, $basepangloria);
$query_Recordset2 = "SELECT IDDEVOLUCION FROM TRNDEVOLUCIONCOMPRA ORDER BY IDDEVOLUCION DESC";
$Recordset2 = mysql_query($query_Recordset2, $basepangloria) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<link href="../../../css/forms.css" rel="stylesheet" type="text/css" />
<script src="../../../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<link href="../../../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<script>
function Confirm(form){

alert("Se ha agregado un nuevo registro!"); 

form.submit();

}

</script>
</head>

<body>
<table width="820" border="0">
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center">
    <tr valign="baseline">
      <td colspan="2" align="right" nowrap="nowrap" bgcolor="#999999" class="encaforms">Devolución de Compra</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">No. de Devolución:</td>
      <td><input name="IDDEVOLUCION" type="text" disabled="disabled" value="<?php echo $row_Recordset2['IDDEVOLUCION']+1; ?>" size="32" readonly="readonly" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Empleado:</td>
      <td><input name="IDEMPLEADO" type="text" value="<?php echo $row_Recordset1['IDUSUARIO']; ?>" size="4" readonly="readonly" />
      <?php echo $row_nombreempleado['NOMBREEMPLEADO']; ?></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">No. de Compra:</td>
      <td><select name="ID_DETENCCOM" onchange="document.form1.send.dissabled=false">
        <?php 
do {  
?>
        <option value="<?php echo $row_compra['ID_DETENCCOM']?>" ><?php echo $row_compra['ID_DETENCCOM']?></option>
        <?php
} while ($row_compra = mysql_fetch_assoc($compra));
?>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Documento a Devolver:</td>
      <td><span id="sprytextfield1">
      <input type="text" name="DOCADEVOLVER" value="" size="32"  onblur="document.form1.send.dissabled=false" />
      <span class="textfieldRequiredMsg">Se necesita un valor.</span><span class="textfieldMinCharsMsg">No se cumple el mínimo de caracteres requerido.</span></span></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Fecha de Devolución:</td>
      <td><input name="FECHADEVOLUCION" type="text" onblur="document.form1.send.dissabled=false" value="<?php echo date("Y-m-d")?>" size="32" readonly="readonly"/></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Importe de Devolución:</td>
      <td><span id="sprytextfield2">
      <input type="text" name="IMPORTE" value="" size="10" onblur="document.form1.send.dissabled=false" />
      <span class="textfieldRequiredMsg">Se necesita un valor.</span><span class="textfieldInvalidFormatMsg">Formato no válido.</span><span class="textfieldMinValueMsg">El valor introducido es inferior al mínimo permitido.</span></span></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Gasto Generado:</td>
      <td><span id="sprytextfield3">
      <input type="text" name="GASTOGENERADO" value="" size="10" onblur="document.form1.send.dissabled=false" />
      <span class="textfieldInvalidFormatMsg">Formato no válido.</span><span class="textfieldMinValueMsg">El valor introducido es inferior al mínimo permitido.</span><span class="textfieldRequiredMsg">Se necesita un valor.</span></span></td>
    </tr>
    <tr valign="baseline">
      <td align="right" valign="top" nowrap="nowrap">Observación:</td>
      <td><textarea name="OBSERVACION" cols="32" rows="8"></textarea></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Insertar registro" dissabled name="send" id="send" /></td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form1" />
</form>
<p>&nbsp;</p>
<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "none", {validateOn:["blur"], minChars:3});
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "currency", {validateOn:["blur"], minValue:0});
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3", "currency", {validateOn:["blur"], minValue:0});
</script>
</body>
</html>
<?php
mysql_free_result($compra);

mysql_free_result($Recordset1);

mysql_free_result($nombreempleado);

mysql_free_result($Recordset2);
?>
