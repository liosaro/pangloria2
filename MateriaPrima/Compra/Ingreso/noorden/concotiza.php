<?php require_once('../../../../Connections/basepangloria.php'); ?>
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO TRNDETALLECOMPRA (IDCOMPRA, IDUNIDAD, ID_DETENCCOM, CANTIDADMATPRIMA, MATERIAPRIMA, PRECIOUNIDAD) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['IDCOMPRA'], "int"),
                       GetSQLValueString($_POST['IDUNIDAD'], "int"),
                       GetSQLValueString($_POST['ID_DETENCCOM'], "int"),
                       GetSQLValueString($_POST['CANTIDADMATPRIMA'], "int"),
                       GetSQLValueString($_POST['MATERIAPRIMA'], "int"),
                       GetSQLValueString($_POST['PRECIOUNIDAD'], "double"));

  mysql_select_db($database_basepangloria, $basepangloria);
  $Result1 = mysql_query($insertSQL, $basepangloria) or die(mysql_error());
}
// consulta para seleccionar el ultimo encabezado de compra
mysql_select_db($database_basepangloria, $basepangloria);
$query_ultimaorden = "SELECT ID_DETENCCOM, IDPROVEEDOR, IDORDEN, IDEMPLEADO, ID_TIPO_FACTURA, IDESTAFACTURA, NOFACTURA, FECHACOMPRA FROM TRNENCABEZADOCOMPRA WHERE ELIMIN = 0 ORDER BY ID_DETENCCOM DESC";
$ultimaorden = mysql_query($query_ultimaorden, $basepangloria) or die(mysql_error());
$row_ultimaorden = mysql_fetch_assoc($ultimaorden);
$totalRows_ultimaorden = mysql_num_rows($ultimaorden);
// consulta para verificar el tipo de factura		
$tipofac= 	$row_ultimaorden['ID_TIPO_FACTURA'];				
mysql_select_db($database_basepangloria, $basepangloria);
$query_TipoFact = "SELECT ID_TIPO_FACTURA, TIPOFACTURA FROM CATTIPOFACTURA WHERE ID_TIPO_FACTURA = $tipofac";
$TipoFact = mysql_query($query_TipoFact, $basepangloria) or die(mysql_error());
$row_TipoFact = mysql_fetch_assoc($TipoFact);
$totalRows_TipoFact = mysql_num_rows($TipoFact);
// Consulta para verificar el nombre del proveedor
$prove= $row_ultimaorden['IDPROVEEDOR'];
mysql_select_db($database_basepangloria, $basepangloria);
$query_Proveedor = "SELECT IDPROVEEDOR, NOMBREPROVEEDOR FROM CATPROVEEDOR WHERE IDPROVEEDOR = $prove";
$Proveedor = mysql_query($query_Proveedor, $basepangloria) or die(mysql_error());
$row_Proveedor = mysql_fetch_assoc($Proveedor);
$totalRows_Proveedor = mysql_num_rows($Proveedor);
//Consulta para verificar el nombre del empleado
$emple= $row_ultimaorden['IDEMPLEADO'];
mysql_select_db($database_basepangloria, $basepangloria);
$query_Emple = "SELECT IDEMPLEADO, NOMBREEMPLEADO FROM CATEMPLEADO WHERE IDEMPLEADO = $emple";
$Emple = mysql_query($query_Emple, $basepangloria) or die(mysql_error());
$row_Emple = mysql_fetch_assoc($Emple);
$totalRows_Emple = mysql_num_rows($Emple);
// consulta para verificar el estado de la factura
$IDFACtu= $row_ultimaorden['IDESTAFACTURA'];
mysql_select_db($database_basepangloria, $basepangloria);
$query_ESTADOFAC = "SELECT IDESTAFACTURA, ESTADO FROM CATESTADOFACTURA WHERE IDESTAFACTURA = $IDFACtu";
$ESTADOFAC = mysql_query($query_ESTADOFAC, $basepangloria) or die(mysql_error());
$row_ESTADOFAC = mysql_fetch_assoc($ESTADOFAC);
$totalRows_ESTADOFAC = mysql_num_rows($ESTADOFAC);
// Consulta de el detalle de la orden de compra
$eNCA= $row_ultimaorden['ID_DETENCCOM']; 
mysql_select_db($database_basepangloria, $basepangloria);
$query_consuldetaorprod = "SELECT * FROM TRNDETALLECOMPRA  WHERE ELIMINA = 0 AND ID_DETENCCOM= $eNCA ";
$consuldetaorprod = mysql_query($query_consuldetaorprod, $basepangloria) or die(mysql_error());
$row_consuldetaorprod = mysql_fetch_assoc($consuldetaorprod);
$totalRows_consuldetaorprod = mysql_num_rows($consuldetaorprod);

mysql_select_db($database_basepangloria, $basepangloria);
$query_Materia = "SELECT IDMATPRIMA, DESCRIPCION FROM CATMATERIAPRIMA WHERE ELIMIN = 0 ORDER BY DESCRIPCION ASC";
$Materia = mysql_query($query_Materia, $basepangloria) or die(mysql_error());
$row_Materia = mysql_fetch_assoc($Materia);
$totalRows_Materia = mysql_num_rows($Materia);

mysql_select_db($database_basepangloria, $basepangloria);
$query_Unidad = "SELECT IDUNIDAD, TIPOUNIDAD FROM CATUNIDADES WHERE ELIMIN = 0 ORDER BY TIPOUNIDAD ASC";
$Unidad = mysql_query($query_Unidad, $basepangloria) or die(mysql_error());
$row_Unidad = mysql_fetch_assoc($Unidad);
$totalRows_Unidad = mysql_num_rows($Unidad);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script src="../../../../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script language="JavaScript">
 function Abrir_ventana (pagina) {
 var opciones="toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=yes, width=508, height=365, top=85, left=140";
 window.open(pagina,"",opciones);
 }
 </script>
<link href="../../../../css/forms.css" rel="stylesheet" type="text/css" />
<link href="../../../../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table width="820" border="0">
  <tr>
    <td align="center" class="encaforms">Ingreso de  Compra</td>
  </tr>
  <tr>
    <td><table width="820" border="0">
      <tr>
        <td class="etifactu">&nbsp;</td>
        <td class="retorno">&nbsp;</td>
        <td class="etifactu">&nbsp;</td>
        <td align="right" class="retorno"><a href="compras.php" target="popup" onclick="window.open(this.href, this.target, 'width=810,height=285,resizable = 0'); return false;"><img src="../../../../imagenes/icono/new.png" width="32" height="32" /></a></td>
      </tr>
      <tr>
        <td width="123" class="etifactu">No. de Compra</td>
        <td width="309" class="retorno"><span class="NO"><?php echo $row_ultimaorden['ID_DETENCCOM']; ?></span></td>
        <td width="95" class="etifactu">Proveedor</td>
        <td width="275" class="retorno"><?php echo $row_Proveedor['NOMBREPROVEEDOR']; ?></td>
      </tr>
      <tr>
        <td class="etifactu">Orden de Compra:</td>
        <td class="retorno"><?php echo $row_ultimaorden['IDORDEN']; ?></td>
        <td class="etifactu">Empleado que Ingresa</td>
        <td class="retorno"><?php echo $row_Emple['NOMBREEMPLEADO']; ?></td>
      </tr>
      <tr>
        <td><span class="etifactu">Fecha:</span></td>
        <td class="retorno"><?php echo $row_ultimaorden['FECHACOMPRA']; ?></td>
        <td>Estado de Factura</td>
        <td align="left" class="retorno"><?php echo $row_ESTADOFAC['ESTADO']; ?></td>
      </tr>
      <tr>
        <td>Tipo de Factura</td>
        <td class="retorno"><?php echo $row_TipoFact['TIPOFACTURA']; ?></td>
        <td>No. Factura de Referencia</td>
        <td align="left" class="retorno"><?php echo $row_ultimaorden['NOFACTURA']; ?></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
      <table align="center">
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Materia Prima:</td>
          <td nowrap="nowrap" align="right"><select name="MATERIAPRIMA" >
            <?php 
do {  
?>
            <option value="<?php echo $row_Materia['IDMATPRIMA']?>" ><?php echo $row_Materia['DESCRIPCION']?></option>
            <?php
} while ($row_Materia = mysql_fetch_assoc($Materia));
?>
          </select></td>
          <td nowrap="nowrap" align="right">Unidad de Medida:</td>
          <td nowrap="nowrap" align="right"><select name="IDUNIDAD" onchange="document.form1.enviar.disabled=false;">
            <?php 
do {  
?>
            <option value="<?php echo $row_Unidad['IDUNIDAD']?>" ><?php echo $row_Unidad['TIPOUNIDAD']?></option>
            <?php
} while ($row_Unidad = mysql_fetch_assoc($Unidad));
?>
          </select></td>
          <td>Cantidad de Materia Prima:</td>
          <td><span id="sprytextfield1">
            <input type="text" name="CANTIDADMATPRIMA" value="" size="9" />
            <span class="textfieldRequiredMsg">Se necesita un valor.</span><span class="textfieldInvalidFormatMsg">Formato no válido.</span></span></td>
          <td>Precio Unitario:</td>
          <td><span id="sprytextfield2">
          <input type="text" name="PRECIOUNIDAD" value="" size="9" />
          <span class="textfieldRequiredMsg">Se necesita un valor.</span><span class="textfieldInvalidFormatMsg">Formato no válido.</span></span></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">&nbsp;</td>
          <td nowrap="nowrap" align="right">&nbsp;</td>
          <td nowrap="nowrap" align="right">&nbsp;</td>
          <td nowrap="nowrap" align="right">&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td><input name="enviar" type="submit" disabled id="enviar" value="Insertar registro"/></td>
        </tr>
      </table>
      <input type="hidden" name="IDCOMPRA" value="" />
      <input type="hidden" name="ID_DETENCCOM" value="<?php echo $row_ultimaorden['ID_DETENCCOM']; ?>" />
      <input type="hidden" name="MM_insert" value="form1" />
    </form></td>
  </tr>
  <tr>
    <td><form action="scriptcompra.php" method="post" target="_self" id="detil">
      <p>&nbsp;</p>
      <table width="820" border="1">
        <tr>
          <td colspan="6" bgcolor="#999999" class="deta">Registros Agregados</td>
          </tr>
        <tr class="retabla">
          <td bgcolor="#000000">Detalle</td>
          <td bgcolor="#000000">Unidad de Peso</td>
          <td bgcolor="#000000">Materia Prima</td>
          <td bgcolor="#000000">Cantidad de Producto</td>
          <td bgcolor="#000000">Precio Unitario</td>
          <td bgcolor="#000000">Costo</td>
        </tr>
        <?php do { ?>
        <?php
		$i= $i+1;
		mysql_select_db($database_basepangloria, $basepangloria);
		$consumat = $row_consuldetaorprod['MATERIAPRIMA'];
$query_consulmatpri = sprintf("SELECT  DESCRIPCION FROM CATMATERIAPRIMA WHERE IDMATPRIMA = '$consumat'");
$consulmatpri = mysql_query($query_consulmatpri, $basepangloria) or die(mysql_error());
$row_consulmatpri = mysql_fetch_assoc($consulmatpri);
$totalRows_consulmatpri = mysql_num_rows($consulmatpri);
$conunidad = $row_consuldetaorprod['IDUNIDAD'];
$query_consulunipeso = "SELECT * FROM CATUNIDADES where IDUNIDAD='$conunidad' ";
$consulunipeso = mysql_query($query_consulunipeso, $basepangloria) or die(mysql_error());
$row_consulunipeso = mysql_fetch_assoc($consulunipeso);
$totalRows_consulunipeso = mysql_num_rows($consulunipeso);
$p= $_POST['desc[$i]'];
$subcosto =($row_consuldetaorprod['CANTIDADMATPRIMA']*$row_consuldetaorprod['PRECIOUNIDAD']);
$coste = ($row_consuldetaorprod['CANTIDADMATPRIMA']*$row_consuldetaorprod['PRECIOUNIDAD']*$p)

 
		?>
        <tr>
          <td height="33"><?php echo $row_consuldetaorprod['IDCOMPRA']; ?></td>
          <td><?php echo $row_consulunipeso['TIPOUNIDAD']; ?></td>
          <td><?php echo $row_consulmatpri['DESCRIPCION']; ?></td>
          <td><?php echo $row_consuldetaorprod['CANTIDADMATPRIMA']; ?></td>
          <td>$<?php echo $row_consuldetaorprod['PRECIOUNIDAD']; ?></td>
          <td><?php echo (($subcosto)-($coste)); ?></td>
        </tr>
        <?php } while ($row_consuldetaorprod = mysql_fetch_assoc($consuldetaorprod)); ?>
      </table>
      <table width="820">
        <tr>
          <td width="708" align="right">Sub-Total</td>
          <td width="100">$<?php 
	$result = mysql_query("Select sum(CANTIDADMATPRIMA * PRECIOUNIDAD ) as 'total' FROM TRNDETALLECOMPRA WHERE ID_DETENCCOM  = $eNCA " );
	$row = mysql_fetch_array($result, MYSQL_ASSOC);
	echo $row['total'];
	 ?></td>
        </tr>
        <tr>
          <td align="right">IVA</td>
          <td>$<?php 
	$result = mysql_query("Select sum(CANTIDADMATPRIMA * PRECIOUNIDAD ) as 'total' FROM TRNDETALLECOMPRA WHERE ID_DETENCCOM  = $eNCA " );
	$row2 = mysql_fetch_array($result, MYSQL_ASSOC);
	$subto= ($row2['total']*0.13);
	$tot = ($subto + $row2['total']);
	echo $subto;
	 ?></td>
        </tr>
        <tr>
          <td align="right">TOTAL</td>
          <td>$<?php echo "$tot" ?></td>
        </tr>
      </table>
      <p>&nbsp; </p>
      <p>&nbsp;</p>
    </form>
    <p>&nbsp;</p></td>
  </tr>
</table>
<p class="etifactu"><span class="retorno"></span></p>
<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "currency", {validateOn:["blur"]});
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "currency", {validateOn:["blur"]});
</script>
</body>
</html>
<?php
mysql_free_result($ultimaorden);

mysql_free_result($TipoFact);

mysql_free_result($Proveedor);

mysql_free_result($Emple);

mysql_free_result($ESTADOFAC);

mysql_free_result($consuldetaorprod);

mysql_free_result($Materia);

mysql_free_result($Unidad);
?>
