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

mysql_select_db($database_basepangloria, $basepangloria);
$query_ultimoenca = "SELECT IdEncabezadoEnInventario, idEmpleado, fechaIngresoInventario, idcompra FROM TrnEncaEntrInventario WHERE ELIMIN = 0 ORDER BY ELIMIN DESC";
$ultimoenca = mysql_query($query_ultimoenca, $basepangloria) or die(mysql_error());
$row_ultimoenca = mysql_fetch_assoc($ultimoenca);
$totalRows_ultimoenca = mysql_num_rows($ultimoenca);

$colname_consulcompra = "-1";
if (isset($_GET['IDCOMPRA'])) {
  $colname_consulcompra = $_GET['IDCOMPRA'];
}
mysql_select_db($database_basepangloria, $basepangloria);
$query_consulcompra = sprintf("SELECT IDCOMPRA, IDUNIDAD, ID_DETENCCOM, CANTIDADMATPRIMA, MATERIAPRIMA FROM TRNDETALLECOMPRA WHERE ID_DETENCCOM = %s", GetSQLValueString($colname_consulcompra, "int"));
$consulcompra = mysql_query($query_consulcompra, $basepangloria) or die(mysql_error());
$row_consulcompra = mysql_fetch_assoc($consulcompra);
$totalRows_consulcompra = mysql_num_rows($consulcompra);
$query_ultimoenca = "SELECT IdEncabezadoEnInventario, idEmpleado, fechaIngresoInventario, idcompra FROM TrnEncaEntrInventario WHERE ELIMIN = 0 ORDER BY ELIMIN DESC";
$ultimoenca = mysql_query($query_ultimoenca, $basepangloria) or die(mysql_error());
$row_ultimoenca = mysql_fetch_assoc($ultimoenca);
$totalRows_ultimoenca = mysql_num_rows($ultimoenca);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="../../../../css/forms.css" rel="stylesheet" type="text/css" />
<link href="../../../css/forms.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table width="820" border="0">
  <tr>
    <td><table width="100%" border="0">
      <tr>
        <td colspan="4" bgcolor="#999999" class="encaforms">Entrada de Materia Prima a Inventario</td>
        </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td align="right"><a href="compras.php" target="popup" onclick="window.open(this.href, this.target, 'width=810,height=285,resizable = 0'); return false;"><img src="../../../imagenes/icono/Invoice-256.png" alt="" width="32" height="32" /></a></td>
      </tr>
      <tr>
        <td>No. de Ingreso:<?php echo $row_ultimoenca['IdEncabezadoEnInventario']; ?></td>
        <td>Codigo de Empleado que Ingresa:<?php echo $row_ultimoenca['idEmpleado']; ?></td>
        <td>No. de Compra:<?php echo $row_ultimoenca['idcompra']; ?></td>
        <td>Fecha de Ingreso:<?php echo $row_ultimoenca['fechaIngresoInventario']; ?></td>
      </tr>
      <tr>
        <td colspan="4"><input name="load" type="button" onclick="location.href='ingresar.php?IDCOMPRA=<?php echo $row_ultimoenca['idcompra']; ?>'" value="Cargar Detalle"  /></td>
        </tr>
      <tr>
        <td colspan="4"><form id="form1" name="form1" method="post" action="script.php?ENCA=<?php echo $row_ultimoenca['IdEncabezadoEnInventario']; ?>&compra=<?php echo $row_ultimoenca['idcompra']; ?>">
          <table width="100%" border="1" cellpadding="0" cellspacing="0">
            <tr class="retabla">
              <td align="center" bgcolor="#000000">Agregar</td>
              <td align="center" bgcolor="#000000">Codigo de Detalle en Compra</td>
              <td align="center" bgcolor="#000000">Unidad de Medida</td>
              <td align="center" bgcolor="#000000">Cantidad</td>
              <td align="center" bgcolor="#000000">Codigo de Materia Prima</td>
            </tr>
            <?php do { ?>
            <tr>
              <td><input name="very[]" type="checkbox" id="very[]" value="<?php echo $row_consulcompra['IDCOMPRA']; ?>" checked="checked" /></td>
              <td><?php echo $row_consulcompra['ID_DETENCCOM']; ?></td>
              <td><?php echo $row_consulcompra['IDUNIDAD']; ?></td>
              <td><?php echo $row_consulcompra['CANTIDADMATPRIMA']; ?></td>
              <td><?php echo $row_consulcompra['MATERIAPRIMA']; ?></td>
            </tr>
            <?php } while ($row_consulcompra = mysql_fetch_assoc($consulcompra)); ?>
          </table>
          <p>
            <input type="submit" name="enviar" id="enviar" value="Enviar"  />
          </p>
        </form>          <p>&nbsp;</p></td>
        </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($ultimoenca);

mysql_free_result($consulcompra);

mysql_free_result($empleado);

mysql_free_result($ultimodetalle);

mysql_free_result($medidatabla);

mysql_free_result($materiatabla);
?>
