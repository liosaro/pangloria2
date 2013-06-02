<?php require_once('../../../Connections/basepangloria.php'); ?>
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
  $insertSQL = sprintf("INSERT INTO TrnEncaEntrInventario (IdEncabezadoEnInventario, idEmpleado, fechaIngresoInventario, idcompra) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($_POST['IdEncabezadoEnInventario'], "int"),
                       GetSQLValueString($_POST['idEmpleado'], "int"),
                       GetSQLValueString($_POST['fechaIngresoInventario'], "date"),
                       GetSQLValueString($_POST['idcompra'], "int"));

  mysql_select_db($database_basepangloria, $basepangloria);
  $Result1 = mysql_query($insertSQL, $basepangloria) or die(mysql_error());
}

mysql_select_db($database_basepangloria, $basepangloria);
$query_emplia = "SELECT IDEMPLEADO, NOMBREEMPLEADO FROM CATEMPLEADO WHERE ELIMIN = 0";
$emplia = mysql_query($query_emplia, $basepangloria) or die(mysql_error());
$row_emplia = mysql_fetch_assoc($emplia);
$totalRows_emplia = mysql_num_rows($emplia);

mysql_select_db($database_basepangloria, $basepangloria);
$query_compr = "SELECT ID_DETENCCOM FROM TRNENCABEZADOCOMPRA WHERE ELIMIN = 0";
$compr = mysql_query($query_compr, $basepangloria) or die(mysql_error());
$row_compr = mysql_fetch_assoc($compr);
$totalRows_compr = mysql_num_rows($compr);

mysql_select_db($database_basepangloria, $basepangloria);
$query_ultimo = "SELECT IdEncabezadoEnInventario FROM TrnEncaEntrInventario WHERE ELIMIN = 0 ORDER BY IdEncabezadoEnInventario DESC";
$ultimo = mysql_query($query_ultimo, $basepangloria) or die(mysql_error());
$row_ultimo = mysql_fetch_assoc($ultimo);
$totalRows_ultimo = mysql_num_rows($ultimo);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<link href="../../../../SpryAssets/bootstrap-combined.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" media="screen"
     href="../../../../css/bootstrap-datetimepicker.min.css">
     <script>
function cerrarse()
{
 opener.location.reload();
 window.close()
}
</script>
<link href="../../../css/forms.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table width="820" border="0">
  <tr>
    <td align="left"><form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
      <table align="left">
        <tr valign="baseline">
          <td colspan="2" align="right" nowrap="nowrap" bgcolor="#999999" class="encaforms">Nuevo Ingreso de Materia Prima</td>
          </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Codigo de Entrada:</td>
          <td><input name="IdEncabezadoEnInventario" type="text" disabled="disabled" value="<?php echo $row_ultimo['IdEncabezadoEnInventario']+1; ?>" size="32" readonly="readonly" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Empleado que Hace El Ingreso:</td>
          <td><select name="idEmpleado">
            <?php 
do {  
?>
            <option value="<?php echo $row_emplia['IDEMPLEADO']?>" ><?php echo $row_emplia['NOMBREEMPLEADO']?></option>
            <?php
} while ($row_emplia = mysql_fetch_assoc($emplia));
?>
            </select></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Fecha de Ingreso:</td>
          <td><input type="text" name="fechaIngresoInventario" value="<?php echo date("Y-m-d");?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Compra que hace el Ingreso:</td>
          <td><select name="idcompra">
            <?php 
do {  
?>
            <option value="<?php echo $row_compr['ID_DETENCCOM']?>" ><?php echo $row_compr['ID_DETENCCOM']?></option>
            <?php
} while ($row_compr = mysql_fetch_assoc($compr));
?>
            </select></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">&nbsp;</td>
          <td><input type="submit" value="Insertar registro" />
            <input name="CERADOR" type="submit" class="label-warning" id="CERADOR" value="Cerrar" onclick="cerrarse()"/></td>
        </tr>
      </table>
      <input type="hidden" name="MM_insert" value="form1" />
    </form></td>
  </tr>
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($emplia);

mysql_free_result($compr);

mysql_free_result($ultimo);
?>
