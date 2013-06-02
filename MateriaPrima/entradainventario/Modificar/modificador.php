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
// Consulta para llenar el Encabezado
if (isset($_GET['root'])) {
  $colname_ultimoenca = $_GET['root'];
}
mysql_select_db($database_basepangloria, $basepangloria);
$query_ultimoenca = sprintf("SELECT IdEncabezadoEnInventario, idEmpleado, fechaIngresoInventario, idcompra FROM TrnEncaEntrInventario WHERE IdEncabezadoEnInventario = %s ORDER BY IdEncabezadoEnInventario DESC", GetSQLValueString($colname_ultimoenca, "int"));
$ultimoenca = mysql_query($query_ultimoenca, $basepangloria) or die(mysql_error());
$row_ultimoenca = mysql_fetch_assoc($ultimoenca);
$totalRows_ultimoenca = mysql_num_rows($ultimoenca);

$colname_detalle = "-1";
if (isset($_GET['root'])) {
  $colname_detalle = $_GET['root'];
}
mysql_select_db($database_basepangloria, $basepangloria);
$query_detalle = sprintf("SELECT IDENTRADA, IdEncabezadoEnInventario, IDUNIDAD, IDMATPRIMA, CANTIDAD FROM TRNENTRADA_INVENTARIO WHERE IdEncabezadoEnInventario = %s AND ELIMIN=0", GetSQLValueString($colname_detalle, "int"));
$detalle = mysql_query($query_detalle, $basepangloria) or die(mysql_error());
$row_detalle = mysql_fetch_assoc($detalle);
$totalRows_detalle = mysql_num_rows($detalle);

$colname_Recordset1 = "-1";
if (isset($_GET['IDCOM'])) {
  $colname_Recordset1 = $_GET['IDCOM'];
}
mysql_select_db($database_basepangloria, $basepangloria);
$query_Recordset1 = sprintf("SELECT IDCOMPRA, IDUNIDAD, ID_DETENCCOM, CANTIDADMATPRIMA, MATERIAPRIMA FROM TRNDETALLECOMPRA WHERE ID_DETENCCOM = %s", GetSQLValueString($colname_Recordset1, "int"));
$Recordset1 = mysql_query($query_Recordset1, $basepangloria) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="../../../../css/forms.css" rel="stylesheet" type="text/css" />
<link href="../../../css/forms.css" rel="stylesheet" type="text/css" />
 <script language="JavaScript">
function aviso(url){
if (!confirm("ALERTA!! va a proceder a eliminar este registro y a restar esta cantidad de materia prima del inventario, si desea eliminarlo de click en ACEPTAR\n de lo contrario de click en CANCELAR.")) {
return false;
}
else {
document.location = url;
return true;
}
}
</script>
</head>

<body>
<table width="820" border="0">
  <tr>
    <td><table width="100%" border="0">
      <tr>
        <td colspan="4" bgcolor="#999999" class="encaforms">Modificar Entrada de Materia Prima a Inventario</td>
      </tr>
      <tr>
        <td>No. de Ingreso:<?php echo $row_ultimoenca['IdEncabezadoEnInventario']; ?></td>
        <td>Codigo de Empleado que Ingresa:<?php echo $row_ultimoenca['idEmpleado']; ?></td>
        <td>No. de Compra:<?php echo $row_ultimoenca['idcompra']; ?></td>
        <td>Fecha de Ingreso:<?php echo $row_ultimoenca['fechaIngresoInventario']; ?></td>
      </tr>
      <tr>
        <td colspan="4" class="deta">Detalle que contiene la Entrada de Inventario</td>
        </tr>
      <tr>
        <td colspan="4" align="left">
          <table width="100%" border="1" cellpadding="0" cellspacing="0">
            <tr class="retabla">
              <td align="center" bgcolor="#000000">Eliminar</td>
              <td align="center" bgcolor="#000000">Codigo de Detalle en Compra</td>
              <td align="center" bgcolor="#000000">Unidad de Medida</td>
              <td align="center" bgcolor="#000000">Cantidad</td>
              <td align="center" bgcolor="#000000">Codigo de Materia Prima</td>
            </tr>
            <?php do { ?>
            <tr>
              <td><a href="javascript:;" onclick="aviso('eliminar.php?root=<?php echo $row_detalle['IDENTRADA'] ?>&mat=<?php echo $row_detalle['IDMATPRIMA']; ?>&canti=<?php echo $row_detalle['CANTIDAD']; ?>'); return false;"><img src="../../../imagenes/icono/delete-32.png" alt="" width="32" height="32" /></a></td>
              <td><?php echo $row_detalle['IDENTRADA']; ?></td>
              <td><?php echo $row_detalle['IDUNIDAD']; ?></td>
              <td><?php echo $row_detalle['CANTIDAD']; ?></td>
              <td><?php echo $row_detalle['IDMATPRIMA']; ?></td>
            </tr>
            <?php } while ($row_detalle = mysql_fetch_assoc($detalle)); ?>
          </table>
          <p>&nbsp;</p>
          <form id="form1" name="form1" method="post" action="script.php?ENCA=<?php echo $row_ultimoenca['IdEncabezadoEnInventario']; ?>">
            <table width="100%" border="1" cellpadding="0" cellspacing="0">
              <tr class="retabla">
                <td colspan="5" align="center" bgcolor="#999999"><span class="deta">Detalle que contiene la Entrada de Inventario</span></td>
                </tr>
              <tr class="retabla">
                <td align="center" bgcolor="#000000">Agregar</td>
                <td align="center" bgcolor="#000000">Codigo de Detalle en Compra</td>
                <td align="center" bgcolor="#000000">Unidad de Medida</td>
                <td align="center" bgcolor="#000000">Cantidad</td>
                <td align="center" bgcolor="#000000">Codigo de Materia Prima</td>
              </tr>
              <?php do { ?>
              <tr>
                <td><input name="very[]2" type="checkbox" id="very[]2" value="<?php echo $row_Recordset1['IDCOMPRA']; ?>" checked="checked" /></td>
                <td><?php echo $row_Recordset1['IDCOMPRA']; ?></td>
                <td><?php echo $row_Recordset1['IDUNIDAD']; ?></td>
                <td><?php echo $row_Recordset1['CANTIDADMATPRIMA']; ?></td>
                <td><?php echo $row_Recordset1['MATERIAPRIMA']; ?></td>
              </tr>
              <?php } while ($row_Recordset1 = mysql_fetch_assoc($Recordset1)); ?>
            </table>
            <p>
              <input type="submit" name="enviar" id="enviar" value="Enviar"  />
            </p>
          </form>
          <p>&nbsp;</p></td>
        </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($ultimoenca);

mysql_free_result($detalle);

mysql_free_result($Recordset1);

?>
