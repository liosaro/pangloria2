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

$maxRows_filtromate = 10;
$pageNum_filtromate = 0;
if (isset($_GET['pageNum_filtromate'])) {
  $pageNum_filtromate = $_GET['pageNum_filtromate'];
}
$startRow_filtromate = $pageNum_filtromate * $maxRows_filtromate;

$colname_filtromate = "-1";
if (isset($_POST['filtro'])) {
  $colname_filtromate = $_POST['filtro'];
}
mysql_select_db($database_basepangloria, $basepangloria);
$query_filtromate = sprintf("SELECT * FROM CATMATERIAPRIMA WHERE UBICACIONBODEGA = %s ORDER BY UBICACIONBODEGA ASC", GetSQLValueString($colname_filtromate, "int"));
$query_limit_filtromate = sprintf("%s LIMIT %d, %d", $query_filtromate, $startRow_filtromate, $maxRows_filtromate);
$filtromate = mysql_query($query_limit_filtromate, $basepangloria) or die(mysql_error());
$row_filtromate = mysql_fetch_assoc($filtromate);

if (isset($_GET['totalRows_filtromate'])) {
  $totalRows_filtromate = $_GET['totalRows_filtromate'];
} else {
  $all_filtromate = mysql_query($query_filtromate);
  $totalRows_filtromate = mysql_num_rows($all_filtromate);
}
$totalPages_filtromate = ceil($totalRows_filtromate/$maxRows_filtromate)-1;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
</head>

<body><iframe src="modificarmateria.php" name="modificar2" width="850" height="400" scrolling="auto"></iframe>
<table border="0">
  <tr>
    <td>Modificar</td>
    <td>IDMATPRIMA</td>
    <td>IDTIPO</td>
    <td>DESCRIPCION</td>
    <td>UBICACIONBODEGA</td>
    <td>CantDisponible</td>
  </tr>
  <?php do { ?>
    <tr>
      <td><a href="modificarmateria.php?root=<?php echo $row_filtromate['IDMATPRIMA']; ?>"target="modificar">Modificar</a></td>
      <td><?php echo $row_filtromate['IDMATPRIMA']; ?></td>
      <td><?php echo $row_filtromate['IDTIPO']; ?></td>
      <td><?php echo $row_filtromate['DESCRIPCION']; ?></td>
      <td><?php echo $row_filtromate['UBICACIONBODEGA']; ?></td>
      <td><?php echo $row_filtromate['CantDisponible']; ?></td>
    </tr>
    <?php } while ($row_filtromate = mysql_fetch_assoc($filtromate)); ?>
</table>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($filtromate);
?>
