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

$maxRows_consulta = 10;
$pageNum_consulta = 0;
if (isset($_GET['pageNum_consulta'])) {
  $pageNum_consulta = $_GET['pageNum_consulta'];
}
$startRow_consulta = $pageNum_consulta * $maxRows_consulta;
mysql_select_db($database_basepangloria, $basepangloria);

 if (isset($_GET['fechai']) && isset($_GET['fechaf'])) {

        $fechainicio = '"' . $_GET['fechai'] . '"';

        $fechafin = '"' . $_GET['fechaf'] . '"';

        $row_consulta = "SELECT IdEncabezadoEnInventario, idEmpleado, idcompra,fechaIngresoInventario FROM TrnEncaEntrInventario WHERE fechaIngresoInventario BETWEEN $fechainicio AND $fechafin And ELIMIN=0 AND EDITA=0 ORDER BY fechaIngresoInventario DESC";

        $result_buscar = mysql_query($row_consulta);

        if (!$result_buscar) {
			echo "$fechafin";
			echo "$fechainicio";
            echo "No pudo ejecutarse satisfactoriamente la consulta ($sql) " .
            "en la BD: " . mysql_error();
            //Finalizo la aplicación
            exit;
        }
 }

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<link href="../../../css/forms.css" rel="stylesheet" type="text/css" />
<link href="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.2.2/css/bootstrap-combined.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" media="screen" href="http://tarruda.github.com/bootstrap-datetimepicker/assets/css/bootstrap-datetimepicker.min.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body>
<table width="820" border="0">
  <tr>
    <td>
    
        <h5 style="margin-left: 15px ;">Filtrar por Fecha</h5>
            <span style="margin-left: 15px ;" class="add-on">Fecha Inicio</span><input id="fechai" type="date">    
            <span style="margin-left: 15px ;" class="add-on">Fecha Fin</span><input id="fechaf" type="date">
            <button style="margin-left: 15px ;" class="btn btn-warning" onclick="window.open('?fechai='+fechai.value+'&fechaf='+fechaf.value+'','_self');">Buscar</button>       
      
    </td>
  </tr>
</table>
<table width="820" border="1" cellpadding="0" cellspacing="0">
  <tr>
    <td>Codigo de Entrada a Inventario</td>
    <td>Codigo de Empleado</td>
    <td>Codigo de Compra</td>
    <td>Fecha de Ingreso</td>
  </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $result['IdEncabezadoEnInventario']; ?></td>
      <td><?php echo $result['idEmpleado']; ?></td>
      <td><?php echo $result['idcompra']; ?></td>
      <td><?php echo $result['fechaIngresoInventario']; ?></td>
      <td><a href="modificador.php?root=<?php echo $result['IdEncabezadoEnInventario']; ?>&IDCOM=<?php echo $result['idcompra']; ?>"><img src="../../../imagenes/icono/modi.png" width="32" height="32" /></a></td>
    </tr>
    <?php } while ($result = mysql_fetch_assoc($result_buscar)); ?>
</table>
</body>
</html>
<?php
mysql_free_result($consulta);
?>
