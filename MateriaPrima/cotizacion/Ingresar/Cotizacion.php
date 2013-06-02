<?php require_once('../../../Connections/basepangloria.php'); ?>
<?php require_once('../../../Connections/basepangloria.php'); ?>
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
// insercion de detalle
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO TRNDETALLECOTIZACION (IDDETALLE, IDMATPRIMA, IDENCABEZADO, IDUNIDAD, CANTPRODUCTO, PRECIOUNITARIO) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['IDDETALLE'], "int"),
                       GetSQLValueString($_POST['IDMATPRIMA'], "int"),
                       GetSQLValueString($_POST['IDENCABEZADO'], "int"),
                       GetSQLValueString($_POST['IDUNIDAD'], "int"),
                       GetSQLValueString($_POST['CANTPRODUCTO'], "int"),
                       GetSQLValueString($_POST['PRECIOUNITARIO'], "double"));

  mysql_select_db($database_basepangloria, $basepangloria);
  $Result1 = mysql_query($insertSQL, $basepangloria) or die(mysql_error());
}
// consulta para llenar el encabezado
mysql_select_db($database_basepangloria, $basepangloria);
$query_encacotiza = "SELECT IDENCABEZADO, IDVENDEDOR, IDPROVEEDOR, IDEMPLEADO, IDCONDICION, FECHACOTIZACION, VALIDEZOFERTA, PLAZOENTREGA FROM TRNCABEZACOTIZACION WHERE ELIMIN = 0 ORDER BY IDENCABEZADO DESC";
$encacotiza = mysql_query($query_encacotiza, $basepangloria) or die(mysql_error());
$row_encacotiza = mysql_fetch_assoc($encacotiza);
$totalRows_encacotiza = mysql_num_rows($encacotiza);
// consulta de vendedor de proveddor
$vende = $row_encacotiza['IDVENDEDOR'];
mysql_select_db($database_basepangloria, $basepangloria);
$query_vendeprovee = "SELECT NOM FROM CATVENDEDOR_PROV WHERE IDVENDEDOR = $vende";
$vendeprovee = mysql_query($query_vendeprovee, $basepangloria) or die(mysql_error());
$row_vendeprovee = mysql_fetch_assoc($vendeprovee);
$totalRows_vendeprovee = mysql_num_rows($vendeprovee);
// consulto el nombre del proveedor
$provee= $row_encacotiza['IDPROVEEDOR'];
mysql_select_db($database_basepangloria, $basepangloria);
$query_proveedor = "SELECT NOMBREPROVEEDOR FROM CATPROVEEDOR WHERE IDPROVEEDOR = $provee";
$proveedor = mysql_query($query_proveedor, $basepangloria) or die(mysql_error());
$row_proveedor = mysql_fetch_assoc($proveedor);
$totalRows_proveedor = mysql_num_rows($proveedor);
// consulta para determinar el tipo de condicion de pago
$idcon= $row_encacotiza['IDCONDICION'];
mysql_select_db($database_basepangloria, $basepangloria);
$query_condicion = "SELECT TIPO FROM CATCONDICIONPAGO WHERE IDCONDICION = $idcon";
$condicion = mysql_query($query_condicion, $basepangloria) or die(mysql_error());
$row_condicion = mysql_fetch_assoc($condicion);
$totalRows_condicion = mysql_num_rows($condicion);
// consulta empleado de la empesa
$emplia= $row_encacotiza['IDEMPLEADO'];
mysql_select_db($database_basepangloria, $basepangloria);
$query_empleado = "SELECT NOMBREEMPLEADO FROM CATEMPLEADO where IDEMPLEADO= $emplia ";
$empleado = mysql_query($query_empleado, $basepangloria) or die(mysql_error());
$row_empleado = mysql_fetch_assoc($empleado);
$totalRows_empleado = mysql_num_rows($empleado);
// consulta para llenar el combo de materia prima
mysql_select_db($database_basepangloria, $basepangloria);
$query_materia = "SELECT IDMATPRIMA, DESCRIPCION FROM CATMATERIAPRIMA WHERE ELIMIN = 0 ORDER BY DESCRIPCION ASC";
$materia = mysql_query($query_materia, $basepangloria) or die(mysql_error());
$row_materia = mysql_fetch_assoc($materia);
$totalRows_materia = mysql_num_rows($materia);
// consulta para llenar el combo de unidad
mysql_select_db($database_basepangloria, $basepangloria);
$query_unidad = "SELECT IDUNIDAD, TIPOUNIDAD FROM CATUNIDADES WHERE ELIMIN = 0 ORDER BY TIPOUNIDAD ASC";
$unidad = mysql_query($query_unidad, $basepangloria) or die(mysql_error());
$row_unidad = mysql_fetch_assoc($unidad);
$totalRows_unidad = mysql_num_rows($unidad);
// consulta para mostrar los registros agregados
$encabe = $row_encacotiza['IDENCABEZADO'];
mysql_select_db($database_basepangloria, $basepangloria);
$query_Agregados = "SELECT IDDETALLE, IDMATPRIMA, IDUNIDAD, CANTPRODUCTO, PRECIOUNITARIO FROM TRNDETALLECOTIZACION WHERE IDENCABEZADO = $encabe ORDER BY IDDETALLE DESC";
$Agregados = mysql_query($query_Agregados, $basepangloria) or die(mysql_error());
$row_Agregados = mysql_fetch_assoc($Agregados);
$totalRows_Agregados = mysql_num_rows($Agregados);


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<link href="../../../css/forms.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table width="820" border="0">
  <tr>
    <td><table width="100%" border="0">
      <tr>
        <td colspan="6" bgcolor="#999999" class="encaforms">Ingreso de Cotizacion</td>
        </tr>
      <tr>
        <td>Cotizacion No.:</td>
        <td class="NO"><?php echo $row_encacotiza['IDENCABEZADO']; ?></td>
        <td> Vendedor del Proveedor:</td>
        <td class="retorno"><?php echo $row_vendeprovee['NOM']; ?></td>
        <td>Proveedor:</td>
        <td class="retorno"><?php echo $row_proveedor['NOMBREPROVEEDOR']; ?></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>Valides de la Oferta:</td>
        <td class="retorno"><?php echo $row_encacotiza['VALIDEZOFERTA']; ?> dias</td>
        <td>Plazo de Entrega:</td>
        <td class="retorno"><?php echo $row_encacotiza['PLAZOENTREGA']; ?> dias</td>
      </tr>
      <tr>
        <td>Codigo de Empleado Ingresa:</td>
        <td class="retorno"><?php echo $row_empleado['NOMBREEMPLEADO']; ?></td>
        <td>Condicion de Pago:</td>
        <td class="retorno"><?php echo $row_condicion['TIPO']; ?></td>
        <td>Fecha de Ingreso:</td>
        <td class="retorno"><?php echo $row_encacotiza['FECHACOTIZACION']; ?></td>
      </tr>
      <tr>
        <td colspan="6" class="deta">Ingresar Detalle de Cotizacion</td>
        </tr>
      <tr>
        <td colspan="6">&nbsp;
          <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
            <table align="center">
              <tr valign="baseline">
                <td nowrap="nowrap" align="right">IDMATPRIMA:</td>
                <td>&nbsp;</td>
                <td><select name="IDMATPRIMA">
                  <?php 
do {  
?>
                  <option value="<?php echo $row_materia['IDMATPRIMA']?>" ><?php echo $row_materia['DESCRIPCION']?></option>
                  <?php
} while ($row_materia = mysql_fetch_assoc($materia));
?>
                </select></td>
                <td>IDUNIDAD:</td>
                <td><select name="IDUNIDAD">
                  <?php 
do {  
?>
                  <option value="<?php echo $row_unidad['IDUNIDAD']?>" ><?php echo $row_unidad['TIPOUNIDAD']?></option>
                  <?php
} while ($row_unidad = mysql_fetch_assoc($unidad));
?>
                </select></td>
                <td>CANTPRODUCTO:</td>
                <td><input type="text" name="CANTPRODUCTO" value="" size="32" /></td>
              </tr>
              <tr valign="baseline">
                <td nowrap="nowrap" align="right">&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>PRECIOUNITARIO:</td>
                <td><input type="text" name="PRECIOUNITARIO" value="" size="32" /></td>
              </tr>
              <tr valign="baseline">
                <td nowrap="nowrap" align="right">&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td><input type="submit" value="Insertar registro" /></td>
              </tr>
            </table>
            <input type="hidden" name="IDDETALLE" value="" />
            <input type="hidden" name="IDENCABEZADO" value="<?php echo $row_encacotiza['IDENCABEZADO']; ?>" />
            <input type="hidden" name="MM_insert" value="form1" />
          </form>
          <p>&nbsp;</p></td>
      </tr>
      <tr>
        <td colspan="6">&nbsp;
          <table border="1" cellpadding="0" cellspacing="0">
            <tr>
              <td colspan="5" class="deta">Registros Agregados</td>
              </tr>
            <tr>
              <td>IDDETALLE</td>
              <td>IDMATPRIMA</td>
              <td>IDUNIDAD</td>
              <td>CANTPRODUCTO</td>
              <td>PRECIOUNITARIO</td>
            </tr>
            <?php do { ?>
              <tr>
                <td><?php echo $row_Agregados['IDDETALLE']; ?></td>
                <td><?php echo $row_Agregados['IDMATPRIMA']; ?></td>
                <td><?php echo $row_Agregados['IDUNIDAD']; ?></td>
                <td><?php echo $row_Agregados['CANTPRODUCTO']; ?></td>
                <td><?php echo $row_Agregados['PRECIOUNITARIO']; ?></td>
              </tr>
              <?php } while ($row_Agregados = mysql_fetch_assoc($Agregados)); ?>
          </table></td>
        </tr>
    </table>
    <p>&nbsp;</p></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($encacotiza);

mysql_free_result($Agregados);

mysql_free_result($encacotiza);

mysql_free_result($vendeprovee);

mysql_free_result($proveedor);

mysql_free_result($empleado);

mysql_free_result($materia);

mysql_free_result($unidad);

mysql_free_result($condicion);
?>
