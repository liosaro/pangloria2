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


$colname_concoti = "-1";
if (isset($_GET['varia'])) {
  $colname_concoti = $_GET['varia'];
}
mysql_select_db($database_basepangloria, $basepangloria);
$query_concoti = sprintf("SELECT IDDETALLECOMP, IDMATPRIMA, IDUNIDAD, CANTPRODUCTO, PRECIOUNITARIO FROM TRNDETALLEORDENCOMPRA WHERE IDORDEN = %s AND ELIMIN=0 and EDITA=0" , GetSQLValueString($colname_concoti, "int"));
$concoti = mysql_query($query_concoti, $basepangloria) or die(mysql_error());
$row_concoti = mysql_fetch_assoc($concoti);
$totalRows_concoti = mysql_num_rows($concoti);

$IDORD= $_GET['root'];
mysql_select_db($database_basepangloria, $basepangloria);
$query_ULTIMOENCA = "SELECT * FROM TRNENCAORDCOMPRA  WHERE IDORDEN= $IDORD ";
$ULTIMOENCA = mysql_query($query_ULTIMOENCA, $basepangloria) or die(mysql_error());
$row_ULTIMOENCA = mysql_fetch_assoc($ULTIMOENCA);
$totalRows_ULTIMOENCA = mysql_num_rows($ULTIMOENCA);

$colname_consultacotizado = "-1";
if (isset($_GET['IDENCABEZADO'])) {
  $colname_consultacotizado = $_GET['IDENCABEZADO'];
}
mysql_select_db($database_basepangloria, $basepangloria);
$query_consultacotizado = sprintf("SELECT IDDETALLE, IDMATPRIMA, IDUNIDAD, CANTPRODUCTO, PRECIOUNITARIO FROM TRNDETALLECOTIZACION WHERE IDENCABEZADO = %s", GetSQLValueString($colname_consultacotizado, "int"));
$consultacotizado = mysql_query($query_consultacotizado, $basepangloria) or die(mysql_error());
$row_consultacotizado = mysql_fetch_assoc($consultacotizado);
$totalRows_consultacotizado = mysql_num_rows($consultacotizado);

mysql_select_db($database_basepangloria, $basepangloria);
$IDEN=  $row_ULTIMOENCA['NUMEROCOTIZACIO']; 
$query_Recordset1 = "SELECT  IDPROVEEDOR FROM TRNCABEZACOTIZACION WHERE IDENCABEZADO = $IDEN";
$Recordset1 = mysql_query($query_Recordset1, $basepangloria) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

mysql_select_db($database_basepangloria, $basepangloria);
$IDPRO= $row_Recordset1['IDPROVEEDOR'];
$query_provee = "SELECT * FROM CATPROVEEDOR WHERE IDPROVEEDOR = $IDPRO";
$provee = mysql_query($query_provee, $basepangloria) or die(mysql_error());
$row_provee = mysql_fetch_assoc($provee);
$totalRows_provee = mysql_num_rows($provee);

$maxRows_concoti = 10;
$pageNum_concoti = 0;
if (isset($_GET['pageNum_concoti'])) {
  $pageNum_concoti = $_GET['pageNum_concoti'];
}
$startRow_concoti = $pageNum_concoti * $maxRows_concoti;

$colname_concoti = "-1";
if (isset($_GET['coti'])) {
  $colname_concoti = $_GET['coti'];
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

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
<link href="../../../css/forms.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table width="820" border="0">
  <tr>
    <td align="center" class="encaforms"> Orden de Compra</td>
  </tr>
  <tr>
    <td><table width="100%" border="0">
      <tr>
        <td align="left"><p class="deta">Panadería Gloria</p>
          <p class="etiquetauser">Casa Matriz</p>
          <p><span class="etiquetauser">Av. José Matías Delgado Sur No 42 <br />
          El Salvador, Santa Ana  </span></p>
          <p><span class="etiquetauser"> Teléfono  : (503) 2440364</span>1</p></td>
        <td align="center" valign="bottom"><img src="../../../imagenes/logotipo.png" width="150" height="100" /></td>
        <td valign="top"><span class="etifactu">Orden de Compra No.:</span><span class="NO"> <?php echo $row_ULTIMOENCA['IDORDEN']; ?></span></td>
      </tr>
    </table>
      <table width="100%" border="1">
        <tr>
          <td><table width="820" border="0">
            <tr>
              <td class="etifactu">Proveedor:</td>
              <td class="retorno"><?php echo $row_provee['NOMBREPROVEEDOR']; ?></td>
              <td class="etifactu">Numero de Registro</td>
              <td align="left" class="retorno"><?php echo $row_provee['NUMEROREGISTRO']; ?></td>
            </tr>
            <tr>
              <td width="123" class="etifactu">Dirección:</td>
              <td width="254" class="retorno"><?php echo $row_provee['DIRECCIONPROVEEDOR']; ?></td>
              <td width="150" class="etifactu">Cotización que Genera</td>
              <td width="275" class="retorno"><?php echo $row_ULTIMOENCA['NUMEROCOTIZACIO']; ?></td>
            </tr>
            <tr>
              <td class="etifactu">Teléfono:</td>
              <td class="retorno"><?php echo $row_provee['TELEFONOPROVEEDOR']; ?></td>
              <td class="etifactu">Fecha de Entrega</td>
              <td class="retorno"><?php echo $row_ULTIMOENCA['FECHAENTREGA']; ?></td>
            </tr>
            <tr>
              <td colspan="4"></td>
            </tr>
          </table></td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td>
      <table width="820" border="1" cellpadding="0" cellspacing="0">
        <tr>
          <td colspan="6" bgcolor="#999999" class="deta">            <p>Detalles en Orden de Compra</p></td>
        </tr>
        <tr class="retabla">
          <td colspan="6" align="left" bgcolor="#000000"><input name="load" type="button" onclick="location.href='modificador.php?varia=<?php echo $row_ULTIMOENCA['IDORDEN']; ?>&amp;root=<?php echo $row_ULTIMOENCA['IDORDEN']; ?>&amp;IDENCABEZADO=<?php echo $row_ULTIMOENCA['NUMEROCOTIZACIO'];?>'" value="Cargar Detalle"  /></td>
        </tr>
        <tr class="retabla">
          <td width="166" bgcolor="#000000">Numero Referencia</td>
          <td width="166" bgcolor="#000000">Materia Prima</td>
          <td width="144" bgcolor="#000000">Unidad de Medida</td>
          <td width="195" bgcolor="#000000">Cantidad de Producto</td>
          <td width="208" bgcolor="#000000">Precio Unitario</td>
          <td width="208" bgcolor="#000000"> Costo</td>
        </tr>
        <?php do { ?>
        <?php $conuniconcoti = $row_concoti['IDUNIDAD'];
$query_Recordset1 = sprintf("SELECT TIPOUNIDAD FROM CATUNIDADES WHERE IDUNIDAD = '$conuniconcoti'", GetSQLValueString($colname_Recordset1, "int"));
$Recordset1 = mysql_query($query_Recordset1, $basepangloria) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);
$conmatconcoti = $row_concoti['IDMATPRIMA']; 
$query_nommateria = sprintf("SELECT DESCRIPCION FROM CATMATERIAPRIMA WHERE IDMATPRIMA = '$conmatconcoti'", GetSQLValueString($colname_nommateria, "int"));
$nommateria = mysql_query($query_nommateria, $basepangloria) or die(mysql_error());
$row_nommateria = mysql_fetch_assoc($nommateria);
$totalRows_nommateria = mysql_num_rows($nommateria);

?>
        <tr>
          <td><?php echo $row_concoti['IDDETALLECOMP']; ?></td>
          <td><?php echo $row_nommateria['DESCRIPCION']; ?></td>
          <td><?php echo $row_Recordset1['TIPOUNIDAD']; ?></td>
          <td><?php echo $row_concoti['CANTPRODUCTO']; ?></td>
          <td>$<?php echo $row_concoti['PRECIOUNITARIO']; ?></td>
          <td>$<?php echo $row_concoti['PRECIOUNITARIO']*$row_concoti['CANTPRODUCTO'] ; ?></td>
          </tr>
        <?php } while ($row_concoti = mysql_fetch_assoc($concoti)); ?>
      </table>
      <table width="820" border="0">
        <tr>
          <td align="right" bgcolor="#CCCCCC">Total de la Compra</td>
          <td bgcolor="#CCCCCC"><?php 
	$result = mysql_query("Select sum(CANTPRODUCTO * PRECIOUNITARIO ) as total from TRNDETALLEORDENCOMPRA where IDORDEN = " . $_GET['root']);
	$row = mysql_fetch_array($result, MYSQL_ASSOC);
	echo $row['total'];
	 ?></td>
        </tr>
    </table></td>
  </tr>
</table>
<p class="etifactu"><span class="retorno"></span></p>
</body>
</html>
<?php
mysql_free_result($ultimaorden);

mysql_free_result($carcoti);

mysql_free_result($concoti);

mysql_free_result($ULTIMOENCA);

mysql_free_result($consultacotizado);

mysql_free_result($Recordset1);

mysql_free_result($provee);
?>
