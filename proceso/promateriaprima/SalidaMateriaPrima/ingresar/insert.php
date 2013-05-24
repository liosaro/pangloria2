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
mysql_select_db($database_basepangloria, $basepangloria);
$query_ultimoencasali = "SELECT IDENCABEZADOSALMATPRI, IDEMPLEADO, ID_PED_MAT_PRIMA, FECHAYHORASALIDAMATPRIMA FROM TRNENCABEZADOSALIDMATPRIMA ORDER BY IDENCABEZADOSALMATPRI DESC";
$ultimoencasali = mysql_query($query_ultimoencasali, $basepangloria) or die(mysql_error());
$row_ultimoencasali = mysql_fetch_assoc($ultimoencasali);
$totalRows_ultimoencasali = mysql_num_rows($ultimoencasali);

mysql_select_db($database_basepangloria, $basepangloria);
$ulti = $row_ultimoencasali['ID_PED_MAT_PRIMA'];
$query_ultregis = "SELECT IDUNIDAD, IDMATPRIMA, CANTIDADPEDMATPRI,ID_PED_MAT_PRIMA FROM TRNPEDIDO_MAT_PRIMA WHERE ID_ENCAPEDIDO = $ulti ";
$ultregis = mysql_query($query_ultregis, $basepangloria) or die(mysql_error());
$row_ultregis = mysql_fetch_assoc($ultregis);
$totalRows_ultregis = mysql_num_rows($ultregis);

$colname_textusuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_textusuario = $_SESSION['MM_Username'];
}
mysql_select_db($database_basepangloria, $basepangloria);
$query_textusuario = sprintf("SELECT IDUSUARIO FROM CATUSUARIO WHERE NOMBREUSUARIO = %s", GetSQLValueString($colname_textusuario, "text"));
$textusuario = mysql_query($query_textusuario, $basepangloria) or die(mysql_error());
$row_textusuario = mysql_fetch_assoc($textusuario);
$totalRows_textusuario = mysql_num_rows($textusuario);

mysql_select_db($database_basepangloria, $basepangloria);
$query_departa = "SELECT IDDEPTO, DEPARTAMENTO FROM CATDEPARTAMENEMPRESA ORDER BY DEPARTAMENTO ASC";
$departa = mysql_query($query_departa, $basepangloria) or die(mysql_error());
$row_departa = mysql_fetch_assoc($departa);
$totalRows_departa = mysql_num_rows($departa);



mysql_select_db($database_basepangloria, $basepangloria);
$nomemplusua = $row_textusuario['IDUSUARIO'];
$query_emplia = "SELECT IDEMPLEADO, NOMBREEMPLEADO FROM CATEMPLEADO WHERE IDUSUARIO = $nomemplusua";
$emplia = mysql_query($query_emplia, $basepangloria) or die(mysql_error());
$row_emplia = mysql_fetch_assoc($emplia);
$totalRows_emplia = mysql_num_rows($emplia);

$colname_ultdetad = "-1";
if (isset($_GET['IDENCABEORDPROD'])) {
  $colname_ultdetad = $_GET['IDENCABEORDPROD'];
}
$colname_empleado = "-1";
if (isset($_POST['IDEMPLEADO'])) {
  $colname_empleado = $_POST['IDEMPLEADO'];
}
mysql_select_db($database_basepangloria, $basepangloria);
$emple = $row_ultimoencasali['IDEMPLEADO'];
$query_empleado = sprintf("SELECT NOMBREEMPLEADO FROM CATEMPLEADO WHERE IDEMPLEADO = '$emple'");
$empleado = mysql_query($query_empleado, $basepangloria) or die(mysql_error());
$row_empleado = mysql_fetch_assoc($empleado);
$totalRows_empleado = mysql_num_rows($empleado);
$colname_Sucursal = "-1";
if (isset($_GET['IDSUCURSAL'])) {
  $colname_Sucursal = $_GET['IDSUCURSAL'];
}

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
    <td bgcolor="#999999" class="encaforms">Ingreso de Salida de Materia Prima</td>
  </tr>
  <tr>
    <td><table width="100%" border="0" align="left">
  <tr>
    <td>&nbsp;</td>
    <td align="left" class="NO">&nbsp;</td>
    <td>&nbsp;</td>
    <td align="right" class="retorno"><a href="encabeza.php" target="popup" onClick="window.open(this.href, this.target, 'width=810,height=285,resizable = 0'); return false;"><img src="../../../../imagenes/icono/new.png" alt="" width="32" height="32"/></a></td>
  </tr>
  <tr>
    <td align="left">Salida de Materia Prima No.:</td>
    <td align="left" class="NO"><?php echo $row_ultimoencasali['IDENCABEZADOSALMATPRI']; ?></td>
    <td align="left">Empleado:</td>
    <td align="left" class="retorno"><?php echo $row_empleado['NOMBREEMPLEADO']; ?></td>
  </tr>
  <tr>
    <td align="left">Pedido de Materia Prima que Usara:</td>
    <td align="left" class="retorno"><?php echo $row_ultimoencasali['ID_PED_MAT_PRIMA']; ?></td>
    <td align="left">Fecha:</td>
    <td align="left" class="retorno"><?php echo $row_ultimoencasali['FECHAYHORASALIDAMATPRIMA']; ?></td>
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
    <td><form action="script.php" method="post" target="_self"><table width="100%" border="1">
      <tr>
        <td bgcolor="#CCCCCC" class="deta">Registros Solicitados</td>
      </tr>
      <tr>
        <td>
          <table width="100%" border="1" align="left" cellpadding="0" cellspacing="0">
            <tr class="retabla">
              <td width="10%" align="center" bgcolor="#000000">Agregar</td>
              <td width="10%" align="center" bgcolor="#000000">Codigo</td>
              <td width="10%" align="center" bgcolor="#000000">Cantidad</td>
              <td width="20%" align="center" bgcolor="#000000">Medida</td>
              <td width="70%" align="center" bgcolor="#000000">Materia Prima</td>
            </tr>
            <?php do { ?>
            <?php 
 // ACA CONSULTAMOS EL NOMBRE DE LA MATERIA PRIMA BASDO EN EL ID QUE ME VA TOMANDO CADA VES EL WHILE
  mysql_select_db($database_basepangloria, $basepangloria);
$idtemp = $row_ultregis['IDMATPRIMA'];
$query_nombremateria = sprintf("SELECT DESCRIPCION FROM CATMATERIAPRIMA WHERE IDMATPRIMA = '$idtemp'", GetSQLValueString($colname_nombremateria, "int"));
$nombremateria = mysql_query($query_nombremateria, $basepangloria) or die(mysql_error());
$row_nombremateria = mysql_fetch_assoc($nombremateria);
$totalRows_nombremateria = mysql_num_rows($nombremateria);
// ACA CONSULTAMOS EL NOMBRE DE LA UNIDAD BASDO EN EL ID QUE ME VA TOMANDO CADA VES EL WHILE
$idtempunida = $row_ultregis['IDUNIDAD'];
$query_unidamedida = sprintf("SELECT TIPOUNIDAD FROM CATUNIDADES WHERE IDUNIDAD = $idtempunida", GetSQLValueString($colname_unidamedida, "int"));
$unidamedida = mysql_query($query_unidamedida, $basepangloria) or die(mysql_error());
$row_unidamedida = mysql_fetch_assoc($unidamedida);
$totalRows_unidamedida = mysql_num_rows($unidamedida);

			?>
              <tr>
                <td align="center" bgcolor="#CCCCCC"><input type="checkbox" name="very[]" id="very[]" value="<?php echo $row_ultregis['ID_PED_MAT_PRIMA'];?>" />
                  <label for="very[]"></label></td>
                <td align="center" bgcolor="#CCCCCC"><?php echo $row_ultregis['ID_PED_MAT_PRIMA'];?></td>
                <td align="center" bgcolor="#CCCCCC"><?php echo $row_ultregis['CANTIDADPEDMATPRI']; ?></td>
                <td align="center" bgcolor="#999999"><?php echo $row_unidamedida['TIPOUNIDAD']; ?></td>
                <td align="left" bgcolor="#666666"><?php echo $row_nombremateria['DESCRIPCION']; ?></td>
              </tr>
              <?php } while ($row_ultregis = mysql_fetch_assoc($ultregis)); ?>
          </table><input name="" type="submit" value="Enviar" /></td>
      </tr>
    </table></form></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($Sucursal);

mysql_free_result($empleado);

mysql_free_result($ultregis);

mysql_free_result($textusuario);

mysql_free_result($departa);

mysql_free_result($ultimoencasali);

mysql_free_result($emplia);

mysql_free_result($ultdetad);
?>
