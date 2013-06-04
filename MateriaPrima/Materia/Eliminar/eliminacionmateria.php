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

$maxRows_mate = 10;
$pageNum_mate = 0;
if (isset($_GET['pageNum_mate'])) {
  $pageNum_mate = $_GET['pageNum_mate'];
}
$startRow_mate = $pageNum_mate * $maxRows_mate;

mysql_select_db($database_basepangloria, $basepangloria);
$query_mate = "SELECT * FROM CATMATERIAPRIMA ORDER BY IDMATPRIMA ASC";
$query_limit_mate = sprintf("%s LIMIT %d, %d", $query_mate, $startRow_mate, $maxRows_mate);
$mate = mysql_query($query_limit_mate, $basepangloria) or die(mysql_error());
$row_mate = mysql_fetch_assoc($mate);

if (isset($_GET['totalRows_mate'])) {
  $totalRows_mate = $_GET['totalRows_mate'];
} else {
  $all_mate = mysql_query($query_mate);
  $totalRows_mate = mysql_num_rows($all_mate);
}
$totalPages_mate = ceil($totalRows_mate/$maxRows_mate)-1;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
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
</head>

<body>
<table width="820" border="0">
  <tr>
    <td><form id="form1" name="form1" method="post" action=""><iframe src="filtroeliminar.php" name="eliminar" width="820" height="300" scrolling="Auto" id="eliminar"></iframe>
      <p>&nbsp;</p>
      <table border="1">
        <tr>
          <td colspan="6"><label for="textfield"></label>
            <input type="text" name="filtro" id="filtro" />
            <input type="submit" name="button" id="button" value="Enviar" /></td>
          </tr>
        <tr>
          <td>Eliminar</td>
          <td>IDMATPRIMA</td>
          <td>IDTIPO</td>
          <td>DESCRIPCION</td>
          <td>UBICACIONBODEGA</td>
          <td>PrecioUltCompra</td>
          </tr>
        <?php do { ?>
          <tr>
            <td><a href="javascript:;" onclick="aviso('eliminar.php?root=<?php echo $row_mate['IDMATPRIMA'];?>'); return false;">Eliminar</a></td>
            <td><?php echo $row_mate['IDMATPRIMA']; ?></td>
            <td><?php echo $row_mate['IDTIPO']; ?></td>
            <td><?php echo $row_mate['DESCRIPCION']; ?></td>
            <td><?php echo $row_mate['UBICACIONBODEGA']; ?></td>
            <td><?php echo $row_mate['PrecioUltCompra']; ?></td>
            </tr>
          <?php } while ($row_mate = mysql_fetch_assoc($mate)); ?>
      </table>
<p>&nbsp;</p>
    </form></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($mate);
?>
