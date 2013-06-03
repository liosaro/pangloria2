<?php require_once('../../Connections/basepangloria.php'); ?>
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

$maxRows_id = 10;
$pageNum_id = 0;
if (isset($_GET['pageNum_id'])) {
  $pageNum_id = $_GET['pageNum_id'];
}
$startRow_id = $pageNum_id * $maxRows_id;

$colname_id = "-1";
if (isset($_GET['root'])) {
  $colname_id = $_GET['root'];
}
mysql_select_db($database_basepangloria, $basepangloria);
$query_id = sprintf("SELECT * FROM CATMATERIAPRIMA WHERE IDMATPRIMA = %s ORDER BY IDMATPRIMA ASC", GetSQLValueString($colname_id, "int"));
$query_limit_id = sprintf("%s LIMIT %d, %d", $query_id, $startRow_id, $maxRows_id);
$id = mysql_query($query_limit_id, $basepangloria) or die(mysql_error());
$row_id = mysql_fetch_assoc($id);

if (isset($_GET['totalRows_id'])) {
  $totalRows_id = $_GET['totalRows_id'];
} else {
  $all_id = mysql_query($query_id);
  $totalRows_id = mysql_num_rows($all_id);
}
$totalPages_id = ceil($totalRows_id/$maxRows_id)-1;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
</head>

<body>
<table border="1">
  <tr>
    <td colspan="4" align="center" bgcolor="#999999"><h2>Detalle</h2></td>
  </tr>
  <tr>
    <td width="116">Id Materia Prima</td>
    <td width="60">Id Tipo</td>
    <td width="72">Descripcion</td>
    <td width="152">Ubicacion</td>
  </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $row_id['IDMATPRIMA']; ?></td>
      <td><?php echo $row_id['IDTIPO']; ?></td>
      <td><?php echo $row_id['DESCRIPCION']; ?></td>
      <td><?php echo $row_id['UBICACIONBODEGA']; ?></td>
    </tr>
    <?php } while ($row_id = mysql_fetch_assoc($id)); ?>
</table>
</body>
</html>
<?php
mysql_free_result($id);
?>
