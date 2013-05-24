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
  $insertSQL = sprintf("INSERT INTO TRNENCABEZADOSALIDMATPRIMA (IDENCABEZADOSALMATPRI, IDEMPLEADO, ID_PED_MAT_PRIMA, FECHAYHORASALIDAMATPRIMA, USUARIO) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['IDENCABEZADOSALMATPRI'], "int"),
                       GetSQLValueString($_POST['IDEMPLEADO'], "int"),
                       GetSQLValueString($_POST['ID_PED_MAT_PRIMA'], "int"),
                       GetSQLValueString($_POST['FECHAYHORASALIDAMATPRIMA'], "date"),
                       GetSQLValueString($_POST['USUARIO'], "int"));
$pedido = $_POST['ID_PED_MAT_PRIMA'];
$updateSQL = sprintf("UPDATE TRNENCABEZADOPEDMATPRI SET EDITA = 1 WHERE ID_ENCAPEDIDO=$pedido ");
  mysql_select_db($database_basepangloria, $basepangloria);
  $Result1 = mysql_query($insertSQL, $basepangloria) or die(mysql_error());
  $Result2 = mysql_query($updateSQL, $basepangloria) or die(mysql_error());
}

$colname_userid = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_userid = $_SESSION['MM_Username'];
}
mysql_select_db($database_basepangloria, $basepangloria);
$query_userid = sprintf("SELECT IDUSUARIO FROM CATUSUARIO WHERE NOMBREUSUARIO = %s", GetSQLValueString($colname_userid, "text"));
$userid = mysql_query($query_userid, $basepangloria) or die(mysql_error());
$row_userid = mysql_fetch_assoc($userid);
$totalRows_userid = mysql_num_rows($userid);

mysql_select_db($database_basepangloria, $basepangloria);
$query_Recordset1 = "SELECT IDEMPLEADO, NOMBREEMPLEADO FROM CATEMPLEADO ORDER BY NOMBREEMPLEADO ASC";
$Recordset1 = mysql_query($query_Recordset1, $basepangloria) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

mysql_select_db($database_basepangloria, $basepangloria);
$query_Recordset2 = "SELECT ID_ENCAPEDIDO FROM TRNENCABEZADOPEDMATPRI ORDER BY ID_ENCAPEDIDO DESC";
$Recordset2 = mysql_query($query_Recordset2, $basepangloria) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);

mysql_select_db($database_basepangloria, $basepangloria);
$query_Recordset3 = "SELECT IDENCABEZADOSALMATPRI FROM TRNENCABEZADOSALIDMATPRIMA ORDER BY IDENCABEZADOSALMATPRI DESC";
$Recordset3 = mysql_query($query_Recordset3, $basepangloria) or die(mysql_error());
$row_Recordset3 = mysql_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysql_num_rows($Recordset3);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script>
function cerrarse()
{
 opener.location.reload();
 window.close()
}
</script>
<link href="../../../../css/forms.css" rel="stylesheet" type="text/css" /><title>Documento sin título</title>
</head>
<body>
<table width="820" border="0">
  <tr>
    <td bgcolor="#999999" class="encaforms">Nueva Salida de Materia Prima</td>
  </tr>
  <tr>
    <td align="left"><form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
      <table align="left">
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Salida de Materia Prima No.:</td>
          <td><input name="IDENCABEZADOSALMATPRI" type="text" disabled="disabled" value="<?php echo $row_Recordset3['IDENCABEZADOSALMATPRI']+1; ?>" size="9" readonly="readonly" /></td>
          <td>Pedido de Materia Prima No.:</td>
          <td><select name="ID_PED_MAT_PRIMA"  onchange="document.form1.insi.disabled=false;">
            <?php 
do {  
?>
            <option value="<?php echo $row_Recordset2['ID_ENCAPEDIDO']?>" ><?php echo $row_Recordset2['ID_ENCAPEDIDO']?></option>
            <?php
} while ($row_Recordset2 = mysql_fetch_assoc($Recordset2));
?>
          </select></td>
          <td>&nbsp;</td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Empleado que recive:</td>
          <td><select name="IDEMPLEADO" onchange="document.form1.insi.disabled=false;">
            <?php 
do {  
?>
            <option value="<?php echo $row_Recordset1['IDEMPLEADO']?>" ><?php echo $row_Recordset1['NOMBREEMPLEADO']?></option>
            <?php
} while ($row_Recordset1 = mysql_fetch_assoc($Recordset1));
?>
          </select></td>
          <td>Fecha y hora de Salida:</td>
          <td><input type="text" name="FECHAYHORASALIDAMATPRIMA" value="<?php echo date("Y-m-d H:I"); ?>" size="32" /></td>
          <td>&nbsp;</td>
        </tr>
        <tr> </tr>
        <tr> </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right"><input name="USUARIO" type="hidden" id="USUARIO" value="<?php echo $row_userid['IDUSUARIO']; ?>" /></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td><p>
            <input name="insi" type="submit" id="insi" value="Insertar registro" dissabled/>
          </p>
            <p>
              <input type="button" name="button" id="button" value="Cerrar" onclick="cerrarse()" />
            </p></td>
          <td>&nbsp;</td>
        </tr>
        </table>
      <input type="hidden" name="MM_insert" value="form1" />
    </form>
    <p>&nbsp;</p></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($userid);

mysql_free_result($Recordset1);

mysql_free_result($Recordset2);

mysql_free_result($Recordset3);
?>
