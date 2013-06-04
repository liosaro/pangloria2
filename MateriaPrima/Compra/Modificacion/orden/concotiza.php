<?php require_once('../../../../Connections/basepangloria.php'); ?>

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
// consulta para seleccionar el ultimo encabezado de compra
$colname_ultimaorden = "-1";
if (isset($_GET['root'])) {
  $colname_ultimaorden = $_GET['root'];
}
mysql_select_db($database_basepangloria, $basepangloria);
$query_ultimaorden = sprintf("SELECT ID_DETENCCOM, IDPROVEEDOR, IDORDEN, IDEMPLEADO, ID_TIPO_FACTURA, IDESTAFACTURA, NOFACTURA, FECHACOMPRA FROM TRNENCABEZADOCOMPRA WHERE ID_DETENCCOM = %s ORDER BY ID_DETENCCOM DESC", GetSQLValueString($colname_ultimaorden, "int"));
$ultimaorden = mysql_query($query_ultimaorden, $basepangloria) or die(mysql_error());
$row_ultimaorden = mysql_fetch_assoc($ultimaorden);
$totalRows_ultimaorden = mysql_num_rows($ultimaorden);
// consulta para verificar el tipo de factura		
$tipofac= 	$row_ultimaorden['ID_TIPO_FACTURA'];				
mysql_select_db($database_basepangloria, $basepangloria);
$query_TipoFact = "SELECT ID_TIPO_FACTURA, TIPOFACTURA FROM CATTIPOFACTURA WHERE ID_TIPO_FACTURA = $tipofac";
$TipoFact = mysql_query($query_TipoFact, $basepangloria) or die(mysql_error());
$row_TipoFact = mysql_fetch_assoc($TipoFact);
$totalRows_TipoFact = mysql_num_rows($TipoFact);
// Consulta para verificar el nombre del proveedor
$prove= $row_ultimaorden['IDPROVEEDOR'];
mysql_select_db($database_basepangloria, $basepangloria);
$query_Proveedor = "SELECT IDPROVEEDOR, NOMBREPROVEEDOR FROM CATPROVEEDOR WHERE IDPROVEEDOR = $prove";
$Proveedor = mysql_query($query_Proveedor, $basepangloria) or die(mysql_error());
$row_Proveedor = mysql_fetch_assoc($Proveedor);
$totalRows_Proveedor = mysql_num_rows($Proveedor);
//Consulta para verificar el nombre del empleado
$emple= $row_ultimaorden['IDEMPLEADO'];
mysql_select_db($database_basepangloria, $basepangloria);
$query_Emple = "SELECT IDEMPLEADO, NOMBREEMPLEADO FROM CATEMPLEADO WHERE IDEMPLEADO = $emple";
$Emple = mysql_query($query_Emple, $basepangloria) or die(mysql_error());
$row_Emple = mysql_fetch_assoc($Emple);
$totalRows_Emple = mysql_num_rows($Emple);
// consulta para verificar el estado de la factura
$IDFACtu= $row_ultimaorden['IDESTAFACTURA'];
mysql_select_db($database_basepangloria, $basepangloria);
$query_ESTADOFAC = "SELECT IDESTAFACTURA, ESTADO FROM CATESTADOFACTURA WHERE IDESTAFACTURA = $IDFACtu";
$ESTADOFAC = mysql_query($query_ESTADOFAC, $basepangloria) or die(mysql_error());
$row_ESTADOFAC = mysql_fetch_assoc($ESTADOFAC);
$totalRows_ESTADOFAC = mysql_num_rows($ESTADOFAC);
// Consulta de el detalle de la orden de compra
$IDOR=$row_ultimaorden['IDORDEN']; 
mysql_select_db($database_basepangloria, $basepangloria);
$query_consuldetaorprod = "SELECT IDDETALLECOMP, IDORDEN, IDMATPRIMA, IDUNIDAD, CANTPRODUCTO, PRECIOUNITARIO FROM TRNDETALLEORDENCOMPRA WHERE IDORDEN = $IDOR";
$consuldetaorprod = mysql_query($query_consuldetaorprod, $basepangloria) or die(mysql_error());
$row_consuldetaorprod = mysql_fetch_assoc($consuldetaorprod);
$totalRows_consuldetaorprod = mysql_num_rows($consuldetaorprod);
$eNCA= $row_ultimaorden['ID_DETENCCOM'];
mysql_select_db($database_basepangloria, $basepangloria);
$query_registrodetalle = "SELECT IDCOMPRA, IDUNIDAD, CANTIDADMATPRIMA, MATERIAPRIMA, PRECIOUNIDAD FROM TRNDETALLECOMPRA WHERE ID_DETENCCOM = $eNCA";
$registrodetalle = mysql_query($query_registrodetalle, $basepangloria) or die(mysql_error());
$row_registrodetalle = mysql_fetch_assoc($registrodetalle);
$totalRows_registrodetalle = mysql_num_rows($registrodetalle);


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
<link href="../../../../css/forms.css" rel="stylesheet" type="text/css" />
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
        <td align="right" class="retorno">&nbsp;</td>
      </tr>
      <tr>
        <td width="123" class="etifactu">No. de Compra</td>
        <td width="309" class="retorno"><span class="NO"><?php echo $row_ultimaorden['ID_DETENCCOM']; ?></span></td>
        <td width="95" class="etifactu">Proveedor</td>
        <td width="275" class="retorno"><?php echo $row_Proveedor['NOMBREPROVEEDOR']; ?></td>
      </tr>
      <tr>
        <td class="etifactu">Orden de Compra:</td>
        <td class="retorno"><?php echo $row_ultimaorden['IDORDEN']; ?></td>
        <td class="etifactu">Empleado que Ingresa</td>
        <td class="retorno"><?php echo $row_Emple['NOMBREEMPLEADO']; ?></td>
      </tr>
      <tr>
        <td><span class="etifactu">Fecha:</span></td>
        <td class="retorno"><?php echo $row_ultimaorden['FECHACOMPRA']; ?></td>
        <td>Estado de Factura</td>
        <td align="left" class="retorno"><?php echo $row_ESTADOFAC['ESTADO']; ?></td>
      </tr>
      <tr>
        <td>Tipo de Factura</td>
        <td class="retorno"><?php echo $row_TipoFact['TIPOFACTURA']; ?></td>
        <td>No. Factura de Referencia</td>
        <td align="left" class="retorno"><?php echo $row_ultimaorden['NOFACTURA']; ?></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><form action="scriptcompra.php?enca=<?php echo $eNCA ?>" method="post" target="_self" id="detil">
      <table border="1" cellpadding="0" cellspacing="0">
        <tr>
          <td>Eliminar</td>
          <td>IDCOMPRA</td>
          <td>IDUNIDAD</td>
          <td>CANTIDADMATPRIMA</td>
          <td>MATERIAPRIMA</td>
          <td>PRECIOUNIDAD</td>
        </tr>
        <?php do { ?>
          <tr>
            <td><a href="eliminar.php?root=<?php echo $row_registrodetalle['IDCOMPRA']; ?>"><img src="../../../../imagenes/icono/delete-32.png" alt="" width="32" height="32" /></td>
            <td><?php echo $row_registrodetalle['IDCOMPRA']; ?></td>
            <td><?php echo $row_registrodetalle['IDUNIDAD']; ?></td>
            <td><?php echo $row_registrodetalle['CANTIDADMATPRIMA']; ?></td>
            <td><?php echo $row_registrodetalle['MATERIAPRIMA']; ?></td>
            <td><?php echo $row_registrodetalle['PRECIOUNIDAD']; ?></td>
          </tr>
          <?php } while ($row_registrodetalle = mysql_fetch_assoc($registrodetalle)); ?>
      </table>
      <table width="820">
        <tr>
          <td width="708" align="right">Sub-Total</td>
          <td width="100">$<?php 
	$result = mysql_query("Select sum(CANTIDADMATPRIMA * PRECIOUNIDAD ) as 'total' FROM TRNDETALLECOMPRA WHERE ID_DETENCCOM  = $eNCA AND ElIMINA=0 " );
	$row = mysql_fetch_array($result, MYSQL_ASSOC);
	echo $row['total'];
	 ?></td>
        </tr>
        <tr>
          <td align="right">IVA</td>
          <td>$<?php 
	$result = mysql_query("Select sum(CANTIDADMATPRIMA * PRECIOUNIDAD ) as 'total' FROM TRNDETALLECOMPRA WHERE ID_DETENCCOM  = $eNCA AND ElIMINA=0" );
	$row2 = mysql_fetch_array($result, MYSQL_ASSOC);
	$subto= ($row2['total']*0.13);
	$tot = ($subto + $row2['total']);
	echo $subto;
	 ?></td>
        </tr>
        <tr>
          <td align="right">TOTAL</td>
          <td>$<?php echo "$tot" ?></td>
        </tr>
      </table>
      <table width="820" border="1">
        <tr>
          <td colspan="7" bgcolor="#999999"><input name="load" type="button" value="Cargar Detalle de la Orden de Compra" onclick="location.href='concotiza.php?IDOR=<?php echo $row_ultimaorden['IDORDEN']; ?>'"  /></td>
        </tr>
        <tr class="retabla">
          <td bgcolor="#000000">Agregar</td>
          <td bgcolor="#000000">Detalle</td>
          <td bgcolor="#000000">Unidad de Peso</td>
          <td bgcolor="#000000">Materia Prima</td>
          <td bgcolor="#000000">Cantidad de Producto</td>
          <td bgcolor="#000000">Precio Unitario</td>
          <td bgcolor="#000000">Costo</td>
        </tr>
        <?php do { ?>
        <?php
		$i= $i+1;
		mysql_select_db($database_basepangloria, $basepangloria);
		$consumat = $row_consuldetaorprod['IDMATPRIMA'];
$query_consulmatpri = sprintf("SELECT  DESCRIPCION FROM CATMATERIAPRIMA WHERE IDMATPRIMA = '$consumat'");
$consulmatpri = mysql_query($query_consulmatpri, $basepangloria) or die(mysql_error());
$row_consulmatpri = mysql_fetch_assoc($consulmatpri);
$totalRows_consulmatpri = mysql_num_rows($consulmatpri);
$conunidad = $row_consuldetaorprod['IDUNIDAD'];
$query_consulunipeso = "SELECT * FROM CATUNIDADES where IDUNIDAD='$conunidad' ";
$consulunipeso = mysql_query($query_consulunipeso, $basepangloria) or die(mysql_error());
$row_consulunipeso = mysql_fetch_assoc($consulunipeso);
$totalRows_consulunipeso = mysql_num_rows($consulunipeso);
$p= $_POST['desc[$i]'];
$subcosto =($row_consuldetaorprod['CANTPRODUCTO']*$row_consuldetaorprod['PRECIOUNITARIO']);
$coste = ($row_consuldetaorprod['CANTPRODUCTO']*$row_consuldetaorprod['PRECIOUNITARIO']*$p)

 
		?>
        <tr>
          <td height="33"><input type="checkbox" name="very[]2" id="very[]2" value="<?php echo $row_consuldetaorprod['IDDETALLECOMP']; ?>" checked="checked" />
            <label for="very[]2"></label></td>
          <td><?php echo $row_consuldetaorprod['IDDETALLECOMP']; ?></td>
          <td><?php echo $row_consulunipeso['TIPOUNIDAD']; ?></td>
          <td><?php echo $row_consulmatpri['DESCRIPCION']; ?></td>
          <td>$<?php echo $row_consuldetaorprod['CANTPRODUCTO']; ?></td>
          <td>$<?php echo $row_consuldetaorprod['PRECIOUNITARIO']; ?></td>
          <td><?php echo (($subcosto)-($coste)); ?></td>
        </tr>
        <?php } while ($row_consuldetaorprod = mysql_fetch_assoc($consuldetaorprod)); ?>
    </table>
      <p>&nbsp; </p>
      <p>
        <input type="submit" name="sender" id="sender" value="Enviar" />
      </p>
    </form></td>
  </tr>
</table>
<p class="etifactu"><span class="retorno"></span></p>
</body>
</html>
<?php
mysql_free_result($ultimaorden);

mysql_free_result($TipoFact);

mysql_free_result($Proveedor);

mysql_free_result($Emple);

mysql_free_result($ESTADOFAC);

mysql_free_result($consuldetaorprod);

mysql_free_result($registrodetalle);
?>
