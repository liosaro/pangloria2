<?php require_once('../../../Connections/basepangloria.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "37,39";
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

        $row_consulta = "SELECT ID_DETENCCOM,FECHACOMPRA,NOFACTURA  FROM TRNENCABEZADOCOMPRA WHERE FECHACOMPRA BETWEEN $fechainicio AND $fechafin And ELIMIN=0  ORDER BY FECHACOMPRA DESC";

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
    <td>No. de La Compra</td>
    <td>Fecha de de Compra</td>
    <td>No. Documento de Referencia</td>
    <td>Verificar</td>
  </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $result['ID_DETENCCOM']; ?></td>
      <td><?php echo $result['FECHACOMPRA']; ?></td>
      <td><?php echo $result['NOFACTURA']; ?></td>
      <td><a href="concotiza.php?root=<?php echo $result['ID_DETENCCOM']; ?>"><img src="../../../imagenes/icono/3D-Search-32.png" width="32" height="32" /></a></td>
    </tr>
    <?php } while ($result = mysql_fetch_assoc($result_buscar)); ?>
</table>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($consulta);
?>
