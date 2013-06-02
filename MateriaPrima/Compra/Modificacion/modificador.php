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


$colname_concoti = "-1";
if (isset($_GET['varia'])) {
  $colname_concoti = $_GET['varia'];
}
mysql_select_db($database_basepangloria, $basepangloria);
$query_concoti = sprintf("SELECT IDDETALLECOMP, IDMATPRIMA, IDUNIDAD, CANTPRODUCTO, PRECIOUNITARIO FROM TRNDETALLEORDENCOMPRA WHERE IDORDEN = %s AND ELIMIN=0 and EDITA=0" , GetSQLValueString($colname_concoti, "int"));
$concoti = mysql_query($query_concoti, $basepangloria) or die(mysql_error());
$row_concoti = mysql_fetch_assoc($concoti);
$totalRows_concoti = mysql_num_rows($concoti);

$IDORD= $_GET['root'];
mysql_select_db($database_basepangloria, $basepangloria);
$query_ULTIMOENCA = "SELECT * FROM TRNENCAORDCOMPRA  WHERE IDORDEN= $IDORD ";
$ULTIMOENCA = mysql_query($query_ULTIMOENCA, $basepangloria) or die(mysql_error());
$row_ULTIMOENCA = mysql_fetch_assoc($ULTIMOENCA);
$totalRows_ULTIMOENCA = mysql_num_rows($ULTIMOENCA);

$colname_consultacotizado = "-1";
if (isset($_GET['IDENCABEZADO'])) {
  $colname_consultacotizado = $_GET['IDENCABEZADO'];
}
mysql_select_db($database_basepangloria, $basepangloria);
$query_consultacotizado = sprintf("SELECT IDDETALLE, IDMATPRIMA, IDUNIDAD, CANTPRODUCTO, PRECIOUNITARIO FROM TRNDETALLECOTIZACION WHERE IDENCABEZADO = %s", GetSQLValueString($colname_consultacotizado, "int"));
$consultacotizado = mysql_query($query_consultacotizado, $basepangloria) or die(mysql_error());
$row_consultacotizado = mysql_fetch_assoc($consultacotizado);
$totalRows_consultacotizado = mysql_num_rows($consultacotizado);

$maxRows_concoti = 10;
$pageNum_concoti = 0;
if (isset($_GET['pageNum_concoti'])) {
  $pageNum_concoti = $_GET['pageNum_concoti'];
}
$startRow_concoti = $pageNum_concoti * $maxRows_concoti;

$colname_concoti = "-1";
if (isset($_GET['coti'])) {
  $colname_concoti = $_GET['coti'];
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

 <script language="JavaScript">
function aviso(url){
if (!confirm("ALERTA!! va a proceder a eliminar este registro, si desea eliminarlo de click en ACEPTAR\n de lo contrario de click en CANCELAR.")) {
return false;
}
else {
document.location = url;
return true;
}
}
</script>
<link href="../../../css/forms.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table width="820" border="0">
  <tr>
    <td align="center" class="encaforms">Eliminar Orden de Compra</td>
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
        <td width="123" class="etifactu"><span class="etifactu">Codigo de Orden de Compra</span></td>
        <td width="309" class="retorno"><?php echo $row_ULTIMOENCA['IDORDEN']; ?></td>
        <td width="95" class="etifactu">Cotizacion que genera</td>
        <td width="275" class="retorno"><?php echo $row_ULTIMOENCA['NUMEROCOTIZACIO']; ?></td>
      </tr>
      <tr>
        <td class="etifactu">Fecha de Emision</td>
        <td class="retorno"><?php echo $row_ULTIMOENCA['FECHAEMISIONORDCOM']; ?></td>
        <td class="etifactu">Fecha de Entrega</td>
        <td class="retorno"><?php echo $row_ULTIMOENCA['FECHAENTREGA']; ?></td>
      </tr>
      <tr>
        <td colspan="4">&nbsp;</td>
        </tr>
    </table></td>
  </tr>
  <tr>
    <td><form id="form1" name="form1" method="post" action="script.php">
      <table width="820" border="1" cellpadding="0" cellspacing="0">
        <tr>
          <td colspan="7" bgcolor="#999999" class="deta">            <p>Detalles en Orden de Compra</p></td>
          </tr>
        <tr class="retabla">
          <td colspan="7" align="left" bgcolor="#000000"><input name="load" type="button" onclick="location.href='modificador.php?varia=<?php echo $row_ULTIMOENCA['IDORDEN']; ?>&amp;root=<?php echo $row_ULTIMOENCA['IDORDEN']; ?>&amp;IDENCABEZADO=<?php echo $row_ULTIMOENCA['NUMEROCOTIZACIO'];?>'" value="Cargar Detalle"  /></td>
          </tr>
        <tr class="retabla">
          <td width="166" bgcolor="#000000">Numero Referencial</td>
          <td width="166" bgcolor="#000000">Materia Prima</td>
          <td width="144" bgcolor="#000000">Unidad de Medida</td>
          <td width="195" bgcolor="#000000">Cantida de Producto</td>
          <td width="208" bgcolor="#000000">Precio Unitario</td>
          <td width="208" bgcolor="#000000"> Costo</td>
          <td width="208" bgcolor="#000000">Eliminar</td>
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
          <td><?php echo $row_concoti['IDDETALLECOMP']; ?></td>
          <td><?php echo $row_nommateria['DESCRIPCION']; ?></td>
          <td><?php echo $row_Recordset1['TIPOUNIDAD']; ?></td>
          <td><?php echo $row_concoti['CANTPRODUCTO']; ?></td>
          <td>$<?php echo $row_concoti['PRECIOUNITARIO']; ?></td>
          <td>$<?php echo $row_concoti['PRECIOUNITARIO']*$row_concoti['CANTPRODUCTO'] ; ?></td>
          <td><span class="retorno"><a href="javascript:;" onclick="aviso('eliminar.php?root=<?php echo $row_concoti['IDDETALLECOMP']; ?>'); return false;"><img src="../../../imagenes/icono/delete-32.png" width="32" height="32" /></a></span></td>
        </tr>
        <?php } while ($row_concoti = mysql_fetch_assoc($concoti)); ?>
      </table>
      <table width="820" border="0">
        <tr>
          <td align="right" bgcolor="#CCCCCC">Total de la Compra</td>
          <td bgcolor="#CCCCCC"><?php 
	$result = mysql_query("Select sum(CANTPRODUCTO * PRECIOUNITARIO ) as total from TRNDETALLEORDENCOMPRA where IDORDEN = " . $_GET['root']);
	$row = mysql_fetch_array($result, MYSQL_ASSOC);
	echo $row['total'];
	 ?></td>
        </tr>
      </table>
      <table border="1" cellpadding="0" cellspacing="0">
        <tr>
          <td colspan="5" bgcolor="#999999" class="deta">Detalle de la cotizacion</td>
          </tr>
        <tr>
          <td>Agregar</td>
          <td>Materia Prima</td>
          <td>Unidad de Medida</td>
          <td>Cantidad de Producto</td>
          <td>Precio Unitario</td>
        </tr>
        <?php do { ?>
         <?php $conuniconcoti2 = $row_consultacotizado['IDUNIDAD'];
$query_Recordset12 = sprintf("SELECT TIPOUNIDAD FROM CATUNIDADES WHERE IDUNIDAD = '$conuniconcoti2'", GetSQLValueString($colname_Recordset1, "int"));
$Recordset12 = mysql_query($query_Recordset12, $basepangloria) or die(mysql_error());
$row_Recordset12 = mysql_fetch_assoc($Recordset12);
$totalRows_Recordset12 = mysql_num_rows($Recordset12);
$conmatconcoti2 = $row_consultacotizado['IDMATPRIMA']; 
$query_nommateria2 = sprintf("SELECT DESCRIPCION FROM CATMATERIAPRIMA WHERE IDMATPRIMA = '$conmatconcoti2'");
$nommateria2 = mysql_query($query_nommateria2, $basepangloria) or die(mysql_error());
$row_nommateria2 = mysql_fetch_assoc($nommateria2);
$totalRows_nommateria2 = mysql_num_rows($nommateria2);

?>
          <tr>
            <td><input name="very[]" type="checkbox" id="very[]" value="<?php  echo $row_consultacotizado['IDDETALLE']; ?>" checked="checked" /></td>
            <td><?php echo $row_nommateria2['DESCRIPCION']; ?></td>
            <td><?php echo $row_Recordset12['TIPOUNIDAD']; ?></td>
            <td><?php echo $row_consultacotizado['CANTPRODUCTO']; ?></td>
            <td><?php echo $row_consultacotizado['PRECIOUNITARIO']; ?></td>
          </tr>
          <?php } while ($row_consultacotizado = mysql_fetch_assoc($consultacotizado)); ?>
    </table>
<p>
  <input type="submit" name="enviar" id="enviar" value="Enviar"  />
    </p>
    </form></td>
  </tr>
</table>
<p class="etifactu"><span class="retorno"></span></p>
</body>
</html>
<?php
mysql_free_result($ultimaorden);

mysql_free_result($carcoti);

mysql_free_result($concoti);

mysql_free_result($ULTIMOENCA);

mysql_free_result($consultacotizado);
?>