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

$maxRows_Recordset1 = 10;
$pageNum_Recordset1 = 0;
if (isset($_GET['pageNum_Recordset1'])) {
  $pageNum_Recordset1 = $_GET['pageNum_Recordset1'];
}
$startRow_Recordset1 = $pageNum_Recordset1 * $maxRows_Recordset1;

$colname_Recordset1 = "-1";
if (isset($_GET['FECHA'])) {
  $colname_Recordset1 = $_GET['FECHA'];
  $fechainicio = '"' . $_GET['fechai'] . '"';

        $fechafin = '"' . $_GET['fechaf'] . '"';
}
mysql_select_db($database_basepangloria, $basepangloria);
$query_Recordset1 = "SELECT IDENCAENTREPROD FROM TRNENCABEZADOORDENPROD WHERE FECHA BETWEEN $fechainicio AND $fechafin ORDER BY FECHAEMISIONORDCOM DESC";
$query_limit_Recordset1 = sprintf("%s LIMIT %d, %d", $query_Recordset1, $startRow_Recordset1, $maxRows_Recordset1);
$Recordset1 = mysql_query($query_limit_Recordset1, $basepangloria) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);

if (isset($_GET['totalRows_Recordset1'])) {
  $totalRows_Recordset1 = $_GET['totalRows_Recordset1'];
} else {
  $all_Recordset1 = mysql_query($query_Recordset1);
  $totalRows_Recordset1 = mysql_num_rows($all_Recordset1);
}
$totalPages_Recordset1 = ceil($totalRows_Recordset1/$maxRows_Recordset1)-1;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<link href="../../../../css/forms.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table width="820" border="0">
  <tr>
    <td><div class="input-prepend">
      <h5 style="margin-left: 15px ;">Filtrar por Fecha</h5>
      <span style="margin-left: 15px ;" class="add-on">Fecha Inicio</span>
      <input name="fechai" type="date" id="fechai" />
      <span style="margin-left: 15px ;" class="add-on">Fecha Fin</span>
      <input name="fechaf" type="date" id="fechaf" />
      <img src="../../../../imagenes/icono/3D-Search-32.png" alt="" width="32" height="32" onclick="window.open('?fechai='+fechai.value+'&amp;fechaf='+fechaf.value+'','_self');" /></div></td>
  </tr>
  <tr>
    <td><table border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td>IDENCAENTREPROD</td>
        <td>IDORDENPRODUCCION</td>
        <td>IDEMPLEADO</td>
        <td>FECHA</td>
        <td>FECHAHORAUSUA</td>
        <td>ELIMINA</td>
        <td>EDITA</td>
      </tr>
      <?php do { ?>
      <tr>
        <td><?php echo $row_Recordset1['IDENCAENTREPROD']; ?></td>
        <td><?php echo $row_Recordset1['IDORDENPRODUCCION']; ?></td>
        <td><?php echo $row_Recordset1['IDEMPLEADO']; ?></td>
        <td><?php echo $row_Recordset1['FECHA']; ?></td>
        <td><?php echo $row_Recordset1['FECHAHORAUSUA']; ?></td>
        <td><?php echo $row_Recordset1['ELIMINA']; ?></td>
        <td><?php echo $row_Recordset1['EDITA']; ?></td>
      </tr>
      <?php } while ($row_Recordset1 = mysql_fetch_assoc($Recordset1)); ?>
    </table></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($Recordset1);
?>
