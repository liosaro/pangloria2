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
<?php require_once('../../../../Connections/basepangloria.php'); ?>
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form2")) {
  $updateSQL = sprintf("UPDATE TRNJUSTIFICAIONPERMATPRI SET IDENCABEZADO=%s, IDUNIDAD=%s, CANT_PERDIDA=%s, MAT_PRIMA=%s, JUSTIFICACION=%s WHERE ID_PERDIDA=%s",
                       GetSQLValueString($_POST['IDENCABEZADO'], "int"),
                       GetSQLValueString($_POST['IDUNIDAD'], "int"),
                       GetSQLValueString($_POST['CANT_PERDIDA'], "double"),
                       GetSQLValueString($_POST['MAT_PRIMA'], "int"),
                       GetSQLValueString($_POST['JUSTIFICACION'], "text"),
                       GetSQLValueString($_POST['ID_PERDIDA'], "int"));

  mysql_select_db($database_basepangloria, $basepangloria);
  $Result1 = mysql_query($updateSQL, $basepangloria) or die(mysql_error());
}

$colname_concuerpo = "-1";
if (isset($_GET['root'])) {
  $colname_concuerpo = $_GET['root'];
}
mysql_select_db($database_basepangloria, $basepangloria);
$query_concuerpo = sprintf("SELECT * FROM TRNJUSTIFICAIONPERMATPRI WHERE ID_PERDIDA = %s", GetSQLValueString($colname_concuerpo, "int"));
$concuerpo = mysql_query($query_concuerpo, $basepangloria) or die(mysql_error());
$row_concuerpo = mysql_fetch_assoc($concuerpo);
$totalRows_concuerpo = mysql_num_rows($concuerpo);

mysql_select_db($database_basepangloria, $basepangloria);
$query_matpri = "SELECT IDMATPRIMA, DESCRIPCION FROM CATMATERIAPRIMA WHERE ELIMIN = 0 ORDER BY DESCRIPCION ASC";
$matpri = mysql_query($query_matpri, $basepangloria) or die(mysql_error());
$row_matpri = mysql_fetch_assoc($matpri);
$totalRows_matpri = mysql_num_rows($matpri);
$maxRows_encabezado = 10;
$pageNum_encabezado = 0;
if (isset($_GET['pageNum_encabezado'])) {
  $pageNum_encabezado = $_GET['pageNum_encabezado'];
}
$startRow_encabezado = $pageNum_encabezado * $maxRows_encabezado;

$colname_encabezado = "-1";
if (isset($_GET['filtrojust'])) {
  $colname_encabezado = $_GET['filtrojust'];
}
mysql_select_db($database_basepangloria, $basepangloria);
$query_encabezado = sprintf("SELECT * FROM TRNENCABEZADOJUSTPERMATPRIM WHERE IDENCABEZADO = %s AND ELIMIN=0 AND EDTI=0", GetSQLValueString($colname_encabezado, "int"));
$query_limit_encabezado = sprintf("%s LIMIT %d, %d", $query_encabezado, $startRow_encabezado, $maxRows_encabezado);
$encabezado = mysql_query($query_limit_encabezado, $basepangloria) or die(mysql_error());
$row_encabezado = mysql_fetch_assoc($encabezado);

if (isset($_GET['totalRows_encabezado'])) {
  $totalRows_encabezado = $_GET['totalRows_encabezado'];
} else {
  $all_encabezado = mysql_query($query_encabezado);
  $totalRows_encabezado = mysql_num_rows($all_encabezado);
}
$totalPages_encabezado = ceil($totalRows_encabezado/$maxRows_encabezado)-1;

$maxRows_cuerpo = 10;
$pageNum_cuerpo = 0;
if (isset($_GET['pageNum_cuerpo'])) {
  $pageNum_cuerpo = $_GET['pageNum_cuerpo'];
}
$startRow_cuerpo = $pageNum_cuerpo * $maxRows_cuerpo;

$colname_cuerpo = "-1";
if (isset($_GET['filtrojust'])) {
  $colname_cuerpo = $_GET['filtrojust'];
}
mysql_select_db($database_basepangloria, $basepangloria);
$IDENCABE = $row_encabezado['IDENCABEZADO'];
$query_cuerpo = sprintf("SELECT * FROM TRNJUSTIFICAIONPERMATPRI WHERE IDENCABEZADO = $IDENCABE AND ELIMIN =0");
$query_limit_cuerpo = sprintf("%s LIMIT %d, %d", $query_cuerpo, $startRow_cuerpo, $maxRows_cuerpo);
$cuerpo = mysql_query($query_limit_cuerpo, $basepangloria);
$row_cuerpo = mysql_fetch_assoc($cuerpo);

if (isset($_GET['totalRows_cuerpo'])) {
  $totalRows_cuerpo = $_GET['totalRows_cuerpo'];
} else {
  $all_cuerpo = mysql_query($query_cuerpo);
  $totalRows_cuerpo = mysql_num_rows($all_cuerpo);
}
$totalPages_cuerpo = ceil($totalRows_cuerpo/$maxRows_cuerpo)-1;

mysql_select_db($database_basepangloria, $basepangloria);
$query_medi = "SELECT IDUNIDAD, TIPOUNIDAD FROM CATUNIDADES WHERE ELIMIN = 0 ORDER BY TIPOUNIDAD ASC";
$medi = mysql_query($query_medi, $basepangloria) or die(mysql_error());
$row_medi = mysql_fetch_assoc($medi);
$totalRows_medi = mysql_num_rows($medi);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<style type="text/css">
body {
	margin-left: 0px;
	margin-top: 0px;
	text-align: center;
}
</style>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
<table width="820" border="0" align="left">
  <tr>
    <td width="820"><form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
      <table width="820" align="left">
        <tr valign="baseline">
          <td colspan="4" align="center" nowrap="nowrap" bgcolor="#999999" class="encaforms">Modificar Justificacion de Perdida de Materia Prima</td>
          </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Codigo de Encabezado:</td>
          <td align="left" class="NO"><?php echo $row_encabezado['IDENCABEZADO']; ?></td>
          <td>Codigo de empleado:</td>
          <td class="retorno"><?php echo htmlentities($row_encabezado['IDEMPLEADO'], ENT_COMPAT, 'utf-8'); ?></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Orden de Produccion:</td>
          <td class="retorno"><?php echo htmlentities($row_encabezado['IDORDENPRODUCCION'], ENT_COMPAT, 'utf-8'); ?></td>
          <td>Fecha Ingreso:</td>
          <td class="retorno"><?php echo htmlentities($row_encabezado['FECHAINGRESOJUSTIFICA'], ENT_COMPAT, 'utf-8'); ?></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Emplead:</td>
          <td class="retorno"><?php echo htmlentities($row_encabezado['EMPLEADOINGRESA'], ENT_COMPAT, 'utf-8'); ?></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
  </table>
    </form></td>
  </tr>
  <tr>
    <td>&nbsp;
      <form action="<?php echo $editFormAction; ?>" method="post" name="form2" id="form2">
        <table width="100%" align="left
        ">
          <tr valign="baseline">
            <td width="11%">Codigo de Detalle:</td>
            <td width="16%"><?php echo $row_concuerpo['ID_PERDIDA']; ?></td>
            <td width="11%">&nbsp;</td>
            <td width="10%">&nbsp;</td>
            <td width="10%">&nbsp;</td>
            <td width="12%">&nbsp;</td>
            <td width="30%">&nbsp;</td>
          </tr>
          <tr valign="baseline">
            <td>Cantidad:</td>
            <td><input type="text" name="CANT_PERDIDA" value="<?php echo htmlentities($row_concuerpo['CANT_PERDIDA'], ENT_COMPAT, 'utf-8'); ?>" size="9" /></td>
            <td>Unidad de Medida:</td>
            <td><select name="IDUNIDAD">
              <?php 
do {  
?>
              <option value="<?php echo $row_medi['IDUNIDAD']?>" <?php if (!(strcmp($row_medi['IDUNIDAD'], htmlentities($row_concuerpo['IDUNIDAD'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>><?php echo $row_medi['TIPOUNIDAD']?></option>
              <?php
} while ($row_medi = mysql_fetch_assoc($medi));
?>
            </select></td>
            <td>Materia Prima:</td>
            <td><select name="MAT_PRIMA">
              <?php 
do {  
?>
              <option value="<?php echo $row_matpri['IDMATPRIMA']?>" <?php if (!(strcmp($row_matpri['IDMATPRIMA'], htmlentities($row_concuerpo['MAT_PRIMA'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>><?php echo $row_matpri['DESCRIPCION']?></option>
              <?php
} while ($row_matpri = mysql_fetch_assoc($matpri));
?>
            </select></td>
            <td>Justificacion:</td>
          </tr>
          <tr valign="baseline">
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td><textarea name="JUSTIFICACION" cols="32" rows="5"><?php echo htmlentities($row_concuerpo['JUSTIFICACION'], ENT_COMPAT, 'utf-8'); ?></textarea></td>
          </tr>
          <tr valign="baseline">
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td><input type="submit" value="Actualizar registro" /></td>
          </tr>
        </table>
        <input type="hidden" name="IDENCABEZADO" value="<?php echo htmlentities($row_concuerpo['IDENCABEZADO'], ENT_COMPAT, 'utf-8'); ?>" />
        <input type="hidden" name="MM_update" value="form2" />
        <input type="hidden" name="ID_PERDIDA" value="<?php echo $row_concuerpo['ID_PERDIDA']; ?>" />
      </form>
    <p>&nbsp;</p></td>
  </tr>
  <tr>
    <td><table width="820" border="1" cellpadding="0" cellspacing="0">
      <tr>
        <td colspan="8" align="center" class="retabla"><span class="deta">Detalles</span></td>
        </tr>
      <tr class="retabla">
        
        <td bgcolor="#000000">Codigo de Perdida</td>
        <td bgcolor="#000000">Codigo de Encabezado</td>
        <td bgcolor="#000000">Medida</td>
        <td bgcolor="#000000">Cantidad</td>
        <td bgcolor="#000000">Materia Prima</td>
        <td bgcolor="#000000">Justificacion</td>
        <td bgcolor="#000000">Modificar</td>
        <td bgcolor="#000000">Eliminar</td>
        </tr>
      <?php do { ?>
      <?php mysql_select_db($database_basepangloria, $basepangloria);
$buscar = $row_cuerpo['MAT_PRIMA'];
$query_materia = sprintf("SELECT DESCRIPCION FROM CATMATERIAPRIMA WHERE IDMATPRIMA = '$buscar'", GetSQLValueString($colname_materia, "int"));
$materia = mysql_query($query_materia, $basepangloria) or die(mysql_error());
$row_materia = mysql_fetch_assoc($materia);
$totalRows_materia = mysql_num_rows($materia);
$medi = $row_cuerpo['IDUNIDAD'];
$query_conmedi = sprintf("SELECT TIPOUNIDAD FROM CATUNIDADES WHERE IDUNIDAD = '$medi'", GetSQLValueString($colname_conmedi, "int"));
$conmedi = mysql_query($query_conmedi, $basepangloria) or die(mysql_error());
$row_conmedi = mysql_fetch_assoc($conmedi);
$totalRows_conmedi = mysql_num_rows($conmedi);?>
      <tr>
        
        <td><?php echo $row_cuerpo['ID_PERDIDA']; ?></td>
        <td><?php echo $row_cuerpo['IDENCABEZADO']; ?></td>
        <td><?php echo $row_conmedi['TIPOUNIDAD']; ?></td>
        <td><?php echo $row_cuerpo['CANT_PERDIDA']; ?></td>
        <td><?php echo $row_materia['DESCRIPCION']; ?></td>
        <td><?php echo $row_cuerpo['JUSTIFICACION']; ?></td>
        <td align="center"><a href="modificador.php?root=<?php echo $row_cuerpo['ID_PERDIDA']; ?>&filtrojust=<?php echo $row_cuerpo['IDENCABEZADO']; ?>" target="_self"><img src="../../../../imagenes/icono/modi.png" width="32" height="32" /></a></td>
        <td align="center"><a href="javascript:;" onclick="aviso('eliminar.php?id=<?php echo $row_cuerpo['ID_PERDIDA'];?>'); return false;"><img src="../../../../imagenes/icono/delete-32.png" width="32" height="32" /></a></td>
        </tr>
      <?php } while ($row_cuerpo = mysql_fetch_assoc($cuerpo)); ?>
    </table></td>
  </tr>
</table>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($concuerpo);

mysql_free_result($encabezado);

mysql_free_result($cuerpo);

mysql_free_result($medi);

mysql_free_result($matpri);

mysql_free_result($conmedi);

mysql_free_result($materia);

mysql_free_result($encabezado);

mysql_free_result($cuerpojustifica);

mysql_free_result($nombreprod);
?>
