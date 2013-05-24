<?php require_once('../../../../Connections/basepangloria.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

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
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "../../../../seguridad.php";
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


$colname_ultregis = "-1";
if (isset($_GET['enca'])) {
  $colname_ultregis = $_GET['enca'];
}
// este si lo utilizo
mysql_select_db($database_basepangloria, $basepangloria);
$query_ultregis = sprintf("SELECT IDENCABEZADOSALMATPRI, IDEMPLEADO, ID_PED_MAT_PRIMA, FECHAYHORASALIDAMATPRIMA FROM TRNENCABEZADOSALIDMATPRIMA WHERE IDENCABEZADOSALMATPRI = %s", GetSQLValueString($colname_ultregis, "int"));
$ultregis = mysql_query($query_ultregis, $basepangloria) or die(mysql_error());
$row_ultregis = mysql_fetch_assoc($ultregis);
$totalRows_ultregis = mysql_num_rows($ultregis); 
// hasta aca!
// esto lo uso para llenar el combo de unidad de medida
mysql_select_db($database_basepangloria, $basepangloria);
$query_comboMedida = "SELECT * FROM CATUNIDADES";
$comboMedida = mysql_query($query_comboMedida, $basepangloria) or die(mysql_error());
$row_comboMedida = mysql_fetch_assoc($comboMedida);
$totalRows_comboMedida = mysql_num_rows($comboMedida);
// hasta aca
// esta lo uso para llenar el combo de Materia Prima
mysql_select_db($database_basepangloria, $basepangloria);
$query_comboProducto = "SELECT DESCRIPCION, IDMATPRIMA FROM CATMATERIAPRIMA";
$comboProducto = mysql_query($query_comboProducto, $basepangloria) or die(mysql_error());
$row_comboProducto = mysql_fetch_assoc($comboProducto);
$totalRows_comboProducto = mysql_num_rows($comboProducto);
// hasta aaca

$colname_textusuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_textusuario = $_SESSION['MM_Username'];
}
mysql_select_db($database_basepangloria, $basepangloria);
$emplea = $row_ultregis['IDEMPLEADO'];
$query_textusuario = sprintf("SELECT NOMBREEMPLEADO FROM CATEMPLEADO WHERE IDEMPLEADO= $emplea");
$textusuario = mysql_query($query_textusuario, $basepangloria) or die(mysql_error());
$row_textusuario = mysql_fetch_assoc($textusuario);
$totalRows_textusuario = mysql_num_rows($textusuario);
// ESTE LO USO
$colname_conmodi = "-1";
if (isset($_GET['IDOR'])) {
  $colname_conmodi = $_GET['IDOR'];
}
mysql_select_db($database_basepangloria, $basepangloria);
$query_conmodi = sprintf("SELECT * FROM TRNSALIDA_MAT_PRIM WHERE ID_SALIDA = %s", GetSQLValueString($colname_conmodi, "int"));
$conmodi = mysql_query($query_conmodi, $basepangloria) or die(mysql_error());
$row_conmodi = mysql_fetch_assoc($conmodi);
$totalRows_conmodi = mysql_num_rows($conmodi);
// HASTA ACA


// ESTA LA USO PARA LLENAR LA MATERIA PRIMA PRESENTE EN EL ENCABEZADO
mysql_select_db($database_basepangloria, $basepangloria);
$Ultenca = $row_ultregis['IDENCABEZADOSALMATPRI'];
$query_ultdetad = sprintf("SELECT CANTMAT_PRIMA,ID_MATPRIMA,ID_SALIDA,IDUNIDAD FROM TRNSALIDA_MAT_PRIM  WHERE IDENCABEZADOSALMATPRI = '$Ultenca' AND ELIMIN = '0' AND EDITA = '0' ORDER BY IDENCABEZADOSALMATPRI DESC");
$ultdetad = mysql_query($query_ultdetad, $basepangloria) or die(mysql_error());
$row_ultdetad = mysql_fetch_assoc($ultdetad);
$totalRows_ultdetad = mysql_num_rows($ultdetad);// HASTA ACA
// este lo uso 
$colname_empleado = "-1";
if (isset($_POST['IDEMPLEADO'])) {
  $colname_empleado = $_POST['IDEMPLEADO'];
}

mysql_select_db($database_basepangloria, $basepangloria);
$emple = $row_ultregis['IDEMPLEADO'];
$query_empleado = sprintf("SELECT NOMBREEMPLEADO FROM CATEMPLEADO WHERE IDEMPLEADO = '$emple'");
$empleado = mysql_query($query_empleado, $basepangloria) or die(mysql_error());
$row_empleado = mysql_fetch_assoc($empleado);
$totalRows_empleado = mysql_num_rows($empleado); // hasta aca
$colname_Sucursal = "-1";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<link href="../../../../css/forms.css" rel="stylesheet" type="text/css" />
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
    <td bgcolor="#999999" class="encaforms">Salida de Materia Prima</td>
  </tr>
  <tr>
    <td><table width="100%" border="0">
  <tr>
    <td>&nbsp;</td>
    <td align="left" class="NO">&nbsp;</td>
    <td>&nbsp;</td>
    <td align="right" class="retorno">&nbsp;</td>
  </tr>
  <tr>
   
    <td>Salida de Materia Prima No.:</td>
    <td align="center" class="NO"><?php echo $row_ultregis['IDENCABEZADOSALMATPRI']; ?></td>
    <td>Fecha y Orden:</td>
    <td class="retorno"><?php echo $row_ultregis['FECHAYHORASALIDAMATPRIMA']; ?></td>
  </tr>
  <tr>
    <td>Empleado que recive:</td>
    <td class="retorno"><?php echo $row_empleado['NOMBREEMPLEADO']; ?></td>
    <td>Pedido de Materia Prima No.:</td>
    <td class="retorno"><?php echo $row_ultregis['ID_PED_MAT_PRIMA']; ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td class="retorno">&nbsp;</td>
    <td>&nbsp;</td>
    <td align="right" class="retorno">&nbsp;</td>
  </tr>
    </table>
</td>
  </tr>
  <tr>
    <td><table width="100%" border="1">
      <tr>
        <td bgcolor="#CCCCCC" class="deta">Registros Agregados</td>
      </tr>
      <tr>
        <td>
          <table width="100%" border="1" align="left" cellpadding="0" cellspacing="0">
            <tr class="retabla">
              <td width="10%" align="center" bgcolor="#000000">Codigo</td>
              <td width="10%" align="center" bgcolor="#000000">Cantidad</td>
              <td width="20%" align="center" bgcolor="#000000">Medida</td>
              <td width="70%" align="center" bgcolor="#000000">Producto</td>
              </tr>
            <?php do { ?>
            <?php 
			mysql_select_db($database_basepangloria, $basepangloria);
$filprod = $row_ultdetad['ID_MATPRIMA'];
$filmedi = $row_ultdetad['IDUNIDAD'];
$query_Medida = "SELECT TIPOUNIDAD FROM CATUNIDADES where IDUNIDAD = '$filmedi'";
$Medida = mysql_query($query_Medida, $basepangloria) or die(mysql_error());
$row_Medida = mysql_fetch_assoc($Medida);
$totalRows_Medida = mysql_num_rows($Medida);
$query_Producto = "SELECT DESCRIPCION FROM CATMATERIAPRIMA WHERE IDMATPRIMA = '$filprod'";
$Producto = mysql_query($query_Producto, $basepangloria) or die(mysql_error());
$row_Producto = mysql_fetch_assoc($Producto);
$totalRows_Producto = mysql_num_rows($Producto);

			?>
              <tr>
                <td align="center" bgcolor="#CCCCCC"><?php echo $row_ultdetad['ID_SALIDA']; ?></td>
                <td align="center" bgcolor="#CCCCCC"><?php echo $row_ultdetad['CANTMAT_PRIMA']; ?></td>
                <td align="center" bgcolor="#999999"><?php echo $row_Medida['TIPOUNIDAD']; ?></td>
                <td align="left" bgcolor="#666666"><?php echo $row_Producto['DESCRIPCION']; ?></td>
                </tr>
              <?php } while ($row_ultdetad = mysql_fetch_assoc($ultdetad)); ?>
          </table></td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($Sucursal);

mysql_free_result($empleado);

mysql_free_result($ultregis);

mysql_free_result($comboMedida);

mysql_free_result($comboProducto);

mysql_free_result($textusuario);

mysql_free_result($conmodi);

mysql_free_result($ultdetad);
?>
