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
$query_ultimaorden = "SELECT ID_DETENCCOM, IDPROVEEDOR, IDORDEN, IDEMPLEADO, ID_TIPO_FACTURA, IDESTAFACTURA, NOFACTURA, FECHACOMPRA FROM TRNENCABEZADOCOMPRA WHERE ELIMIN = 0 ORDER BY ID_DETENCCOM DESC";
$ultimaorden = mysql_query($query_ultimaorden, $basepangloria) or die(mysql_error());
$row_ultimaorden = mysql_fetch_assoc($ultimaorden);
$totalRows_ultimaorden = mysql_num_rows($ultimaorden);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="JavaScript">
 function Abrir_ventana (pagina) {
 var opciones="toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=yes, width=508, height=365, top=85, left=140";
 window.open(pagina,"",opciones);
 }
 </script>
<link href="../../../css/forms.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table width="820" border="0">
  <tr>
    <td align="center" class="encaforms">Ingreso de  Compra</td>
  </tr>
  <tr>
    <td><table width="820" border="0">
      <tr>
        <td class="etifactu">&nbsp;</td>
        <td class="retorno">&nbsp;</td>
        <td class="etifactu">&nbsp;</td>
        <td align="right" class="retorno"><a href="compras.php" target="popup" onclick="window.open(this.href, this.target, 'width=810,height=285,resizable = 0'); return false;"><img src="../../../imagenes/icono/Invoice-256.png" width="32" height="32" /></a></td>
      </tr>
      <tr>
        <td width="123" class="etifactu">No. de Compra</td>
        <td width="309" class="retorno"><span class="NO"><?php echo $row_ultimaorden['ID_DETENCCOM']; ?></span></td>
        <td width="95" class="etifactu">Proveedor</td>
        <td width="275" class="retorno"><?php echo $row_ultimaorden['IDPROVEEDOR']; ?></td>
      </tr>
      <tr>
        <td class="etifactu">Orden de Produccion:</td>
        <td class="retorno"><?php echo $row_ultimaorden['IDORDEN']; ?></td>
        <td class="etifactu">Empleado que Ingresa</td>
        <td class="retorno"><?php echo $row_ultimaorden['IDEMPLEADO']; ?></td>
      </tr>
      <tr>
        <td><span class="etifactu">Fehca:</span></td>
        <td class="retorno"><?php echo $row_ultimaorden['FECHACOMPRA']; ?></td>
        <td>Estado de Factura</td>
        <td align="left" class="retorno"><?php echo $row_ultimaorden['IDESTAFACTURA']; ?></td>
      </tr>
      <tr>
        <td>Tipo de Factura</td>
        <td class="retorno"><?php echo $row_ultimaorden['ID_TIPO_FACTURA']; ?></td>
        <td>No. Factura de Referencia</td>
        <td align="left" class="retorno"><?php echo $row_ultimaorden['NOFACTURA']; ?></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><form id="form1" name="form1" method="post" action="script.php">
      <table width="820" border="1" cellpadding="0" cellspacing="0">
        <tr>
          <td colspan="7" bgcolor="#999999"><input name="load" type="button" value="Cargar Detalle" onclick="location.href='concotiza.php?varia=<?php echo $row_ULTIMOENCA['NUMEROCOTIZACIO']; ?>'"  /></td>
          </tr>
        <tr class="retabla">
          <td width="166" bgcolor="#000000">Agregar</td>
          <td width="166" bgcolor="#000000">Numero Referencial</td>
          <td width="166" bgcolor="#000000">Materia Prima</td>
          <td width="144" bgcolor="#000000">Unidad de Medida</td>
          <td width="195" bgcolor="#000000">Cantida de Producto</td>
          <td width="208" bgcolor="#000000">Precio Unitario</td>
          <td width="208" bgcolor="#000000"> Costo</td>
        </tr>
        <?php do { ?>
        <?php $conuniconcoti = $row_concoti['IDUNIDAD'];
$query_Recordset1 = sprintf("SELECT TIPOUNIDAD FROM CATUNIDADES WHERE IDUNIDAD = '$conuniconcoti'", GetSQLValueString($colname_Recordset1, "int"));
$Recordset1 = mysql_query($query_Recordset1, $basepangloria) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);
$conmatconcoti = $row_concoti['IDMATPRIMA']; 
$query_nommateria = sprintf("SELECT DESCRIPCION FROM CATMATERIAPRIMA WHERE IDMATPRIMA = '$conmatconcoti'", GetSQLValueString($colname_nommateria, "int"));
$nommateria = mysql_query($query_nommateria, $basepangloria) or die(mysql_error());
$row_nommateria = mysql_fetch_assoc($nommateria);
$totalRows_nommateria = mysql_num_rows($nommateria);

?>
        <tr>
          <td><input name="very[]" type="checkbox" id="very[]" value="<?php echo $row_concoti['IDDETALLE']; ?>" checked="checked" /></td>
          <td><?php echo $row_concoti['IDDETALLE']; ?></td>
          <td><?php echo $row_nommateria['DESCRIPCION']; ?></td>
          <td><?php echo $row_Recordset1['TIPOUNIDAD']; ?></td>
          <td><?php echo $row_concoti['CANTPRODUCTO']; ?></td>
          <td>$<?php echo $row_concoti['PRECIOUNITARIO']; ?></td>
          <td>$<?php echo $row_concoti['PRECIOUNITARIO']*$row_concoti['CANTPRODUCTO'] ; ?></td>
        </tr>
        <?php } while ($row_concoti = mysql_fetch_assoc($concoti)); ?>
      </table>
      <table width="820" border="0">
        <tr>
          <td align="right" bgcolor="#CCCCCC">Total de la Compra</td>
          <td bgcolor="#CCCCCC">$<?php 
	$result = mysql_query("Select sum(CANTPRODUCTO * PRECIOUNITARIO ) as total from TRNDETALLECOTIZACION where IDENCABEZADO = " . $_GET['varia']);
	$row = mysql_fetch_array($result, MYSQL_ASSOC);
	echo $row['total'];
	 ?></td>
        </tr>
      </table>
      <input type="submit" name="enviar" id="enviar" value="Enviar"  />
    </form></td>
  </tr>
</table>
<p class="etifactu"><span class="retorno"></span></p>
</body>
</html>
<?php
mysql_free_result($ultimaorden);

mysql_free_result($concoti);
?>
