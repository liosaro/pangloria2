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

$maxRows_nombre = 10;
$pageNum_nombre = 0;
if (isset($_GET['pageNum_nombre'])) {
  $pageNum_nombre = $_GET['pageNum_nombre'];
}
$startRow_nombre = $pageNum_nombre * $maxRows_nombre;

$colname_nombre = "-1";
if (isset($_GET['root'])) {
  $colname_nombre = $_GET['root'];
}
mysql_select_db($database_basepangloria, $basepangloria);
$query_nombre = sprintf("SELECT * FROM CATSUCURSAL WHERE NOMBRESUCURSAL LIKE %s AND ELIMIN = '0' ORDER BY IDSUCURSAL ASC", GetSQLValueString("%" . $colname_nombre . "%", "text"));
$query_limit_nombre = sprintf("%s LIMIT %d, %d", $query_nombre, $startRow_nombre, $maxRows_nombre);
$nombre = mysql_query($query_limit_nombre, $basepangloria) or die(mysql_error());
$row_nombre = mysql_fetch_assoc($nombre);

if (isset($_GET['totalRows_nombre'])) {
  $totalRows_nombre = $_GET['totalRows_nombre'];
} else {
  $all_nombre = mysql_query($query_nombre);
  $totalRows_nombre = mysql_num_rows($all_nombre);
}
$totalPages_nombre = ceil($totalRows_nombre/$maxRows_nombre)-1;

$queryString_nombre = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_nombre") == false && 
        stristr($param, "totalRows_nombre") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_nombre = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_nombre = sprintf("&totalRows_nombre=%d%s", $totalRows_nombre, $queryString_nombre);
?>
<link href="../../../css/forms.css" rel="stylesheet" type="text/css" />
<table border="1">
  <tr>
    <td colspan="4" align="center" bgcolor="#999999"><h1>Detalle</h1></td>
  </tr>
  <tr>
    <td colspan="4"><a href="<?php printf("%s?pageNum_nombre=%d%s", $currentPage, 0, $queryString_nombre); ?>"><img src="../../../imagenes/icono/Back-32.png" width="32" height="32" /></a><a href="<?php printf("%s?pageNum_nombre=%d%s", $currentPage, max(0, $pageNum_nombre - 1), $queryString_nombre); ?>"><img src="../../../imagenes/icono/Backward-32.png" width="32" height="32" /></a><a href="<?php printf("%s?pageNum_nombre=%d%s", $currentPage, min($totalPages_nombre, $pageNum_nombre + 1), $queryString_nombre); ?>"><img src="../../../imagenes/icono/Forward-32.png" width="32" height="32" /></a><a href="<?php printf("%s?pageNum_nombre=%d%s", $currentPage, $totalPages_nombre, $queryString_nombre); ?>"><img src="../../../imagenes/icono/Next-32.png" width="32" height="32" /> Registros <?php echo ($startRow_nombre + 1) ?> a <?php echo min($startRow_nombre + $maxRows_nombre, $totalRows_nombre) ?> de <?php echo $totalRows_nombre ?> </a></td>
  </tr>
  <tr class="retabla">
    <td align="center" bgcolor="#000000">Código</td>
    <td align="center" bgcolor="#000000">Sucursal</td>
    <td align="center" bgcolor="#000000">Dirección</td>
    <td align="center" bgcolor="#000000">Teléfono</td>
  </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $row_nombre['IDSUCURSAL']; ?></td>
      <td><?php echo $row_nombre['NOMBRESUCURSAL']; ?></td>
      <td><?php echo $row_nombre['DIRECCIONSUCURSAL']; ?></td>
      <td><?php echo $row_nombre['TELEFONOSUCURSAL']; ?></td>
    </tr>
    <?php } while ($row_nombre = mysql_fetch_assoc($nombre)); ?>
</table>
<?php
mysql_free_result($nombre);
?>