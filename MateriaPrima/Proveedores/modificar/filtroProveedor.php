<link href="../../../css/forms.css" rel="stylesheet" type="text/css" />
<iframe src="modificarProveedor.php" name="modificar2" width="850" height="400" scrolling="auto"></iframe>
<p>
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
}if (!function_exists("GetSQLValueString")) {
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

$maxRows_FiltroProveedor = 10;
$pageNum_FiltroProveedor = 0;
if (isset($_GET['pageNum_FiltroProveedor'])) {
  $pageNum_FiltroProveedor = $_GET['pageNum_FiltroProveedor'];
}
$startRow_FiltroProveedor = $pageNum_FiltroProveedor * $maxRows_FiltroProveedor;

$colname_FiltroProveedor = "-1";
if (isset($_POST['FiltroProvee'])) {
  $colname_FiltroProveedor = $_POST['FiltroProvee'];
}
mysql_select_db($database_basepangloria, $basepangloria);
$query_FiltroProveedor = sprintf("SELECT * FROM CATPROVEEDOR WHERE NOMBREPROVEEDOR LIKE %s AND ELIMIN=0  ORDER BY NOMBREPROVEEDOR ASC", GetSQLValueString("%" . $colname_FiltroProveedor . "%", "text"));
$query_limit_FiltroProveedor = sprintf("%s LIMIT %d, %d", $query_FiltroProveedor, $startRow_FiltroProveedor, $maxRows_FiltroProveedor);
$FiltroProveedor = mysql_query($query_limit_FiltroProveedor, $basepangloria) or die(mysql_error());
$row_FiltroProveedor = mysql_fetch_assoc($FiltroProveedor);

if (isset($_GET['totalRows_FiltroProveedor'])) {
  $totalRows_FiltroProveedor = $_GET['totalRows_FiltroProveedor'];
} else {
  $all_FiltroProveedor = mysql_query($query_FiltroProveedor);
  $totalRows_FiltroProveedor = mysql_num_rows($all_FiltroProveedor);
}
$totalPages_FiltroProveedor = ceil($totalRows_FiltroProveedor/$maxRows_FiltroProveedor)-1;

mysql_free_result($FiltroProveedor);
?>
</p>
<table border="1">
  <tr class="retabla">
    <td align="center" bgcolor="#000000">Modificar</td>
    <td align="center" bgcolor="#000000">Código de Proveedor</td>
    <td align="center" bgcolor="#000000">Nombre de Proveedor</td>
    <td align="center" bgcolor="#000000">Giro</td>
    <td align="center" bgcolor="#000000">Numero de Registro</td>
  </tr>
  <?php do { ?>
    <tr>
      <td><a href="modificarProveedor.php?root=<?php echo $row_FiltroProveedor['IDPROVEEDOR']; ?>"target="modificar"><img src="../../../imagenes/icono/modi.png" width="32" height="32" /></a></td>
      <td><?php echo $row_FiltroProveedor['IDPROVEEDOR']; ?></td>
      <td><?php echo $row_FiltroProveedor['NOMBREPROVEEDOR']; ?></td>
      <td><?php echo $row_FiltroProveedor['GIRO']; ?></td>
      <td><?php echo $row_FiltroProveedor['NUMEROREGISTRO']; ?></td>
    </tr>
    <?php } while ($row_FiltroProveedor = mysql_fetch_assoc($FiltroProveedor)); ?>
</table>
