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

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_ultima = 10;
$pageNum_ultima = 0;
if (isset($_GET['pageNum_ultima'])) {
  $pageNum_ultima = $_GET['pageNum_ultima'];
}
$startRow_ultima = $pageNum_ultima * $maxRows_ultima;

$colname_ultima = "-1";
if (isset($_GET['root'])) {
  $colname_ultima = $_GET['root'];
}
mysql_select_db($database_basepangloria, $basepangloria);
$query_ultima = sprintf("SELECT * FROM CATMATERIAPRIMA WHERE DESCRIPCION LIKE %s ORDER BY IDMATPRIMA ASC", GetSQLValueString("%" . $colname_ultima . "%", "text"));
$query_limit_ultima = sprintf("%s LIMIT %d, %d", $query_ultima, $startRow_ultima, $maxRows_ultima);
$ultima = mysql_query($query_limit_ultima, $basepangloria) or die(mysql_error());
$row_ultima = mysql_fetch_assoc($ultima);

if (isset($_GET['totalRows_ultima'])) {
  $totalRows_ultima = $_GET['totalRows_ultima'];
} else {
  $all_ultima = mysql_query($query_ultima);
  $totalRows_ultima = mysql_num_rows($all_ultima);
}
$totalPages_ultima = ceil($totalRows_ultima/$maxRows_ultima)-1;

$queryString_ultima = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_ultima") == false && 
        stristr($param, "totalRows_ultima") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_ultima = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_ultima = sprintf("&totalRows_ultima=%d%s", $totalRows_ultima, $queryString_ultima);
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
    <td colspan="6" align="center"><a href="<?php printf("%s?pageNum_ultima=%d%s", $currentPage, 0, $queryString_ultima); ?>"><img src="../../../imagenes/icono/Back-32.png" width="32" height="32" /></a><a href="<?php printf("%s?pageNum_ultima=%d%s", $currentPage, max(0, $pageNum_ultima - 1), $queryString_ultima); ?>"><img src="../../../imagenes/icono/Backward-32.png" width="32" height="32" /></a><a href="<?php printf("%s?pageNum_ultima=%d%s", $currentPage, min($totalPages_ultima, $pageNum_ultima + 1), $queryString_ultima); ?>"><img src="../../../imagenes/icono/Forward-32.png" width="32" height="32" /></a><a href="<?php printf("%s?pageNum_ultima=%d%s", $currentPage, $totalPages_ultima, $queryString_ultima); ?>"><img src="../../../imagenes/icono/Next-32.png" width="32" height="32" /></a></td>
  </tr>
  <tr>
    <td colspan="6" align="center" bgcolor="#999999"><h2>Detalle</h2></td>
  </tr>
  <tr>
    <td>Id Materia Prima</td>
    <td>Id Tipo</td>
    <td>Descripcion</td>
    <td>Ubicacion</td>
    <td>EDITA</td>
    <td>ELIMIN</td>
  </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $row_ultima['IDMATPRIMA']; ?></td>
      <td><?php echo $row_ultima['IDTIPO']; ?></td>
      <td><?php echo $row_ultima['DESCRIPCION']; ?></td>
      <td><?php echo $row_ultima['UBICACIONBODEGA']; ?></td>
      <td><?php echo $row_ultima['EDITA']; ?></td>
      <td><?php echo $row_ultima['ELIMIN']; ?></td>
    </tr>
    <?php } while ($row_ultima = mysql_fetch_assoc($ultima)); ?>
</table>
</body>
</html>
<?php
mysql_free_result($ultima);
?>
