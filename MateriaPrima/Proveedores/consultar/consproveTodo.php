<?php require_once('../../../Connections/basepangloria.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "36,37,39,43";
$MM_donotCheckaccess = "false";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && false) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "../../../seguridad.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
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

$maxRows_todos = 10;
$pageNum_todos = 0;
if (isset($_GET['pageNum_todos'])) {
  $pageNum_todos = $_GET['pageNum_todos'];
}
$startRow_todos = $pageNum_todos * $maxRows_todos;

mysql_select_db($database_basepangloria, $basepangloria);
$query_todos = "SELECT * FROM CATPROVEEDOR WHERE IDPROVEEDOR AND ELIMIN = '0'  ORDER BY IDPROVEEDOR ASC";
$query_limit_todos = sprintf("%s LIMIT %d, %d", $query_todos, $startRow_todos, $maxRows_todos);
$todos = mysql_query($query_limit_todos, $basepangloria) or die(mysql_error());
$row_todos = mysql_fetch_assoc($todos);

if (isset($_GET['totalRows_todos'])) {
  $totalRows_todos = $_GET['totalRows_todos'];
} else {
  $all_todos = mysql_query($query_todos);
  $totalRows_todos = mysql_num_rows($all_todos);
}
$totalPages_todos = ceil($totalRows_todos/$maxRows_todos)-1;

$queryString_todos = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_todos") == false && 
        stristr($param, "totalRows_todos") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_todos = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_todos = sprintf("&totalRows_todos=%d%s", $totalRows_todos, $queryString_todos);
?>

<link href="../../../css/forms.css" rel="stylesheet" type="text/css" />


<table border="1">
  <tr>
    <td colspan="11" align="center" bgcolor="#999999"><h1>Detalle</h1></td>
  </tr>
  <tr>
    <td colspan="11"><a href="<?php printf("%s?pageNum_todos=%d%s", $currentPage, 0, $queryString_todos); ?>"><img src="../../../imagenes/icono/Back-32.png" width="32" height="32" /></a><a href="<?php printf("%s?pageNum_todos=%d%s", $currentPage, max(0, $pageNum_todos - 1), $queryString_todos); ?>"><img src="../../../imagenes/icono/Backward-32.png" width="32" height="32" /></a><a href="<?php printf("%s?pageNum_todos=%d%s", $currentPage, min($totalPages_todos, $pageNum_todos + 1), $queryString_todos); ?>"><img src="../../../imagenes/icono/Forward-32.png" width="32" height="32" /></a><a href="<?php printf("%s?pageNum_todos=%d%s", $currentPage, $totalPages_todos, $queryString_todos); ?>"><img src="../../../imagenes/icono/Next-32.png" width="32" height="32" /> Registros <?php echo ($startRow_todos + 1) ?> a <?php echo min($startRow_todos + $maxRows_todos, $totalRows_todos) ?> de <?php echo $totalRows_todos ?> </a></td>
  </tr>
  <tr class="retabla">
    <td align="center" bgcolor="#000000">Código</td>
    <td align="center" bgcolor="#000000">Proveedor</td>
    <td align="center" bgcolor="#000000">Código de Pais</td>
    <td align="center" bgcolor="#000000">Código de Departamento</td>
    <td align="center" bgcolor="#000000">Dirección</td>
    <td align="center" bgcolor="#000000">Teléfono</td>
    <td align="center" bgcolor="#000000">Correo Electrónico</td>
    <td align="center" bgcolor="#000000">Fecha de Ingreso</td>
    <td align="center" bgcolor="#000000">Giro</td>
    <td align="center" bgcolor="#000000">No. de Registro</td>
    <td align="center" bgcolor="#000000">WEB</td>
  </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $row_todos['IDPROVEEDOR']; ?></td>
      <td><?php echo $row_todos['NOMBREPROVEEDOR']; ?></td>
      <td><?php echo $row_todos['IDPAIS']; ?></td>
      <td><?php echo $row_todos['DEPTOPAISPROVEEDOR']; ?></td>
      <td><?php echo $row_todos['DIRECCIONPROVEEDOR']; ?></td>
      <td><?php echo $row_todos['TELEFONOPROVEEDOR']; ?></td>
      <td><?php echo $row_todos['CORREOPROVEEDOR']; ?></td>
      <td><?php echo $row_todos['FECHAINGRESOPROVE']; ?></td>
      <td><?php echo $row_todos['GIRO']; ?></td>
      <td><?php echo $row_todos['NUMEROREGISTRO']; ?></td>
      <td><?php echo $row_todos['WEB']; ?></td>
    </tr>
    <?php } while ($row_todos = mysql_fetch_assoc($todos)); ?>
</table>
<style type="text/css">
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
</style>
<?php
mysql_free_result($todos);
?>
