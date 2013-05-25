<?php require_once('../../../../Connections/basepangloria.php'); ?>
<?php require_once('../../../../Connections/basepangloria.php'); ?>
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
  $insertSQL = sprintf("INSERT INTO TRNJUSTIFICAIONPERMATPRI (IDENCABEZADO, IDUNIDAD, CANT_PERDIDA, MAT_PRIMA, JUSTIFICACION) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['IDENCABEZADO'], "int"),
                       GetSQLValueString($_POST['IDUNIDAD'], "int"),
                       GetSQLValueString($_POST['CANT_PERDIDA'], "double"),
                       GetSQLValueString($_POST['MAT_PRIMA'], "int"),
                       GetSQLValueString($_POST['JUSTIFICACION'], "text"));

  mysql_select_db($database_basepangloria, $basepangloria);
  $Result1 = mysql_query($insertSQL, $basepangloria) or die(mysql_error());
}
// en esta consulta cargo todos los datos para el encabezado
mysql_select_db($database_basepangloria, $basepangloria);
$query_ultimoenca = "SELECT IDENCABEZADO, IDEMPLEADO, IDORDENPRODUCCION, FECHAINGRESOJUSTIFICA FROM TRNENCABEZADOJUSTPERMATPRIM ORDER BY IDENCABEZADO DESC";
$ultimoenca = mysql_query($query_ultimoenca, $basepangloria) or die(mysql_error());
$row_ultimoenca = mysql_fetch_assoc($ultimoenca);
$totalRows_ultimoenca = mysql_num_rows($ultimoenca);

mysql_select_db($database_basepangloria, $basepangloria);
$query_combomateria = "SELECT IDMATPRIMA, DESCRIPCION FROM CATMATERIAPRIMA WHERE ELIMIN = 0 ORDER BY DESCRIPCION ASC";
$combomateria = mysql_query($query_combomateria, $basepangloria) or die(mysql_error());
$row_combomateria = mysql_fetch_assoc($combomateria);
$totalRows_combomateria = mysql_num_rows($combomateria);

mysql_select_db($database_basepangloria, $basepangloria);
$query_comomedida = "SELECT IDUNIDAD, TIPOUNIDAD FROM CATUNIDADES WHERE ELIMIN = 0 ORDER BY TIPOUNIDAD ASC";
$comomedida = mysql_query($query_comomedida, $basepangloria) or die(mysql_error());
$row_comomedida = mysql_fetch_assoc($comomedida);
$totalRows_comomedida = mysql_num_rows($comomedida);
// en este consulta llleno la tabla que me muestra los ultimos registros agregados al detalle
mysql_select_db($database_basepangloria, $basepangloria);
$IDENCABEZADO = $row_ultimoenca['IDENCABEZADO'];
$query_ultimodetalle = "SELECT ID_PERDIDA, IDUNIDAD, CANT_PERDIDA, MAT_PRIMA, JUSTIFICACION FROM TRNJUSTIFICAIONPERMATPRI WHERE IDENCABEZADO = $IDENCABEZADO";
$ultimodetalle = mysql_query($query_ultimodetalle, $basepangloria) or die(mysql_error());
$row_ultimodetalle = mysql_fetch_assoc($ultimodetalle);
$totalRows_ultimodetalle = mysql_num_rows($ultimodetalle);


// en esta consulta tomo el valor del id del ultimo encabezado y lo asigno a una variable para obtener su nombre
mysql_select_db($database_basepangloria, $basepangloria);
$empli = $row_ultimoenca['IDEMPLEADO'];
$query_empleado = "SELECT NOMBREEMPLEADO FROM CATEMPLEADO WHERE IDEMPLEADO = IDEMPLEADO";
$empleado = mysql_query($query_empleado, $basepangloria) or die(mysql_error());
$row_empleado = mysql_fetch_assoc($empleado);
$totalRows_empleado = mysql_num_rows($empleado);


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="../../../../css/forms.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table width="810" border="0">
  <tr>
    <td class="encaforms">Ingresar Justificacion Pedida de Materia Prima</td>
  </tr>
  <tr>
    <td><table width="100%" border="0">
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td align="right"><a href="encabeza.php" target="popup" onclick="window.open(this.href, this.target, 'width=810,height=285,resizable = 0'); return false;"><img src="../../../../imagenes/icono/new.png" alt="" width="32" height="32"/></a></td>
      </tr>
      <tr>
        <td>Justificacion de Perdida de Materia Prima No.:</td>
        <td class="NO"><?php echo $row_ultimoenca['IDENCABEZADO']; ?></td>
        <td>Orden de Produccion No.:</td>
        <td class="retorno"><?php echo $row_ultimoenca['IDORDENPRODUCCION']; ?></td>
      </tr>
      <tr>
        <td>Empleado Que Pierde Materi Prima:</td>
        <td><span class="etiquetauser"><?php echo $row_ultimoenca['IDEMPLEADO']; ?></span>-<span class="retorno"><?php echo $row_empleado['NOMBREEMPLEADO']; ?></span></td>
        <td>Fecha</td>
        <td class="retorno"><?php echo $row_ultimoenca['FECHAINGRESOJUSTIFICA']; ?></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td>&nbsp;
      <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
        <table width="100%" align="center">
          <tr valign="baseline">
            <td width="16%" align="right" nowrap="nowrap">Unidad de Medida:</td>
            <td width="6%"><select name="IDUNIDAD">
              <?php 
do {  
?>
              <option value="<?php echo $row_comomedida['IDUNIDAD']?>" ><?php echo $row_comomedida['TIPOUNIDAD']?></option>
              <?php
} while ($row_comomedida = mysql_fetch_assoc($comomedida));
?>
            </select></td>
            <td width="8%">Cantidad:</td>
            <td width="8%"><input type="text" name="CANT_PERDIDA" value="" size="9" /></td>
            <td width="14%">Materia Prima:</td>
            <td width="9%"><select name="MAT_PRIMA">
              <?php 
do {  
?>
              <option value="<?php echo $row_combomateria['IDMATPRIMA']?>" ><?php echo $row_combomateria['DESCRIPCION']?></option>
              <?php
} while ($row_combomateria = mysql_fetch_assoc($combomateria));
?>
            </select></td>
            <td width="39%">Justificacion:</td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td><textarea name="JUSTIFICACION" cols="50" rows="5"></textarea></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td align="right"><input type="submit" value="Insertar registro" /></td>
          </tr>
        </table>
        <input type="hidden" name="IDENCABEZADO" value="<?php echo $row_ultimoenca['IDENCABEZADO']; ?>" />
        <input type="hidden" name="MM_insert" value="form1" />
      </form>
    <p>&nbsp;</p></td>
  </tr>
  <tr>
    <td align="center"><span class="deta">&nbsp;Registros Agregado</span>
<table width="100%" border="1" cellpadding="0" cellspacing="0">
        <tr class="retabla">
          <td bgcolor="#000000">Codigo</td>
          <td bgcolor="#000000">Unidad de Medida</td>
          <td bgcolor="#000000">Cantidad</td>
          <td bgcolor="#000000">Materia Prima</td>
          <td bgcolor="#000000">Justificacion</td>
        </tr>
        <?php do { ?>
        <?php
		// ESTA CONSULTA SE HACE PARA CAMBIAR EL ID DE LA MATERIA PRIMA Y LA UNIDAD POR SU DESCRIPCION
		mysql_select_db($database_basepangloria, $basepangloria);
		$IDMATPRIMA = $row_ultimodetalle['MAT_PRIMA'];
		$IDUNIDAD = $row_ultimodetalle['IDUNIDAD'];
$query_materiatabla = "SELECT DESCRIPCION FROM CATMATERIAPRIMA WHERE IDMATPRIMA = $IDMATPRIMA";
$materiatabla = mysql_query($query_materiatabla, $basepangloria) or die(mysql_error());
$row_materiatabla = mysql_fetch_assoc($materiatabla);
$totalRows_materiatabla = mysql_num_rows($materiatabla);
$query_medidatabla = "SELECT TIPOUNIDAD FROM CATUNIDADES WHERE IDUNIDAD = $IDUNIDAD";
$medidatabla = mysql_query($query_medidatabla, $basepangloria) or die(mysql_error());
$row_medidatabla = mysql_fetch_assoc($medidatabla);
$totalRows_medidatabla = mysql_num_rows($medidatabla);

		?>
          <tr>
            <td><?php echo $row_ultimodetalle['ID_PERDIDA']; ?></td>
            <td><?php echo $row_medidatabla['TIPOUNIDAD']; ?></td>
            <td><?php echo $row_ultimodetalle['CANT_PERDIDA']; ?></td>
            <td><?php echo $row_materiatabla['DESCRIPCION']; ?></td>
            <td><?php echo $row_ultimodetalle['JUSTIFICACION']; ?></td>
          </tr>
          <?php } while ($row_ultimodetalle = mysql_fetch_assoc($ultimodetalle)); ?>
    </table></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($empleado);

mysql_free_result($ultimoenca);

mysql_free_result($combomateria);

mysql_free_result($comomedida);

mysql_free_result($ultimodetalle);

mysql_free_result($medidatabla);

mysql_free_result($materiatabla);
?>
