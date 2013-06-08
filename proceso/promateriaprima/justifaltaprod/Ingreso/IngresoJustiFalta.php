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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form2")) {
  $insertSQL = sprintf("INSERT INTO TRNJUSTIFICACIONFALTAPRODUCTO (ID_JUSTIFICACION, IDCONTROLPRODUCCION, CANTIDA_FALTANTE, IDPRODUCTOFALTA, ID_MEDIDA, FECHAINGRESOJUSFAPROD, JUSTIFICACIONFALTAPROD) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['ID_JUSTIFICACION'], "int"),
                       GetSQLValueString($_POST['IDCONTROLPRODUCCION'], "int"),
                       GetSQLValueString($_POST['CANTIDA_FALTANTE'], "double"),
                       GetSQLValueString($_POST['IDPRODUCTOFALTA'], "int"),
                       GetSQLValueString($_POST['ID_MEDIDA'], "int"),
                       GetSQLValueString($_POST['FECHAINGRESOJUSFAPROD'], "date"),
                       GetSQLValueString($_POST['JUSTIFICACIONFALTAPROD'], "text"));

  mysql_select_db($database_basepangloria, $basepangloria);
  $Result1 = mysql_query($insertSQL, $basepangloria) or die(mysql_error());
}

mysql_select_db($database_basepangloria, $basepangloria);
$query_justifi = "SELECT ID_JUSTIFICACION FROM TRNJUSTIFICACIONFALTAPRODUCTO";
$justifi = mysql_query($query_justifi, $basepangloria) or die(mysql_error());
$row_justifi = mysql_fetch_assoc($justifi);
$totalRows_justifi = mysql_num_rows($justifi);mysql_select_db($database_basepangloria, $basepangloria);
$query_justifi = "SELECT ID_JUSTIFICACION FROM TRNJUSTIFICACIONFALTAPRODUCTO ORDER BY ID_JUSTIFICACION DESC";
$justifi = mysql_query($query_justifi, $basepangloria) or die(mysql_error());
$row_justifi = mysql_fetch_assoc($justifi);
$totalRows_justifi = mysql_num_rows($justifi);

mysql_select_db($database_basepangloria, $basepangloria);
$query_control = "SELECT ID_CONTROLPRODUCCION FROM TRNCONTROL_DEPRODUCCION";
$control = mysql_query($query_control, $basepangloria) or die(mysql_error());
$row_control = mysql_fetch_assoc($control);
$totalRows_control = mysql_num_rows($control);

mysql_select_db($database_basepangloria, $basepangloria);
$query_producto = "SELECT IDPRODUCTO, DESCRIPCIONPRODUC FROM CATPRODUCTO";
$producto = mysql_query($query_producto, $basepangloria) or die(mysql_error());
$row_producto = mysql_fetch_assoc($producto);
$totalRows_producto = mysql_num_rows($producto);

mysql_select_db($database_basepangloria, $basepangloria);
$query_medidas = "SELECT * FROM CATMEDIDAS";
$medidas = mysql_query($query_medidas, $basepangloria) or die(mysql_error());
$row_medidas = mysql_fetch_assoc($medidas);
$totalRows_medidas = mysql_num_rows($medidas);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.2.2/css/bootstrap-combined.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" media="screen"
href="http://tarruda.github.com/bootstrap-datetimepicker/assets/css/bootstrap-datetimepicker.min.css">

<script src="../../../../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<link href="../../../../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table width="820" border="1">
  <tr>
    <td><form id="form1" name="form1" method="post" action="">
      <table width="100%" border="1">
        <tr>
          <td align="left" bgcolor="#999999"><h1>Ingreso de Justificación de Producto</h1></td>
        </tr>
      </table>
    </form>
      <form action="<?php echo $editFormAction; ?>" method="post" name="form2" id="form2">
        <table align="left">
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">No. de Justificación</td>
            <td nowrap="nowrap"><input name="ID_JUSTIFICACION" type="text" disabled="disabled"  value="<?php echo $row_justifi['ID_JUSTIFICACION']+1; ?>" size="32" readonly="readonly" /></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">No. de control de Producto en Horno</td>
            <td><select name="IDCONTROLPRODUCCION">
              <?php
do {  
?>
              <option value="<?php echo $row_control['ID_CONTROLPRODUCCION']?>"><?php echo $row_control['ID_CONTROLPRODUCCION']?></option>
              <?php
} while ($row_control = mysql_fetch_assoc($control));
  $rows = mysql_num_rows($control);
  if($rows > 0) {
      mysql_data_seek($control, 0);
	  $row_control = mysql_fetch_assoc($control);
  }
?>
            </select></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">Cantidad Faltante</td>
            <td><span id="sprytextfield1">
              <input type="text" name="CANTIDA_FALTANTE" value="" size="32" />
            <span class="textfieldRequiredMsg">Se necesita un valor.</span></span></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">Producto Faltante</td>
            <td><select name="IDPRODUCTOFALTA" id="IDPRODUCTOFALTA">
              <?php
do {  
?>
              <option value="<?php echo $row_producto['IDPRODUCTO']?>"><?php echo $row_producto['DESCRIPCIONPRODUC']?></option>
              <?php
} while ($row_producto = mysql_fetch_assoc($producto));
  $rows = mysql_num_rows($producto);
  if($rows > 0) {
      mysql_data_seek($producto, 0);
	  $row_producto = mysql_fetch_assoc($producto);
  }
?>
            </select></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">Unidad de Medida:</td>
            <td><select name="ID_MEDIDA">
              <?php
do {  
?>
              <option value="<?php echo $row_medidas['ID_MEDIDA']?>"><?php echo $row_medidas['MEDIDA']?></option>
              <?php
} while ($row_medidas = mysql_fetch_assoc($medidas));
  $rows = mysql_num_rows($medidas);
  if($rows > 0) {
      mysql_data_seek($medidas, 0);
	  $row_medidas = mysql_fetch_assoc($medidas);
  }
?>
            </select></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right"><p>Fecha de Ingreso</p></td>
            <td><script type="text/javascript"

src="http://cdnjs.cloudflare.com/ajax/libs/jquery/1.8.3/jquery.min.js">

</script>

<script type="text/javascript"

src="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.2.2/js/bootstrap.min.js">

</script>

<script type="text/javascript"

src="http://tarruda.github.com/bootstrap-datetimepicker/assets/js/bootstrap-datetimepicker.min.js">

</script>

<script type="text/javascript"

src="http://tarruda.github.com/bootstrap-datetimepicker/assets/js/bootstrap-datetimepicker.pt-BR.js">

</script> <div id="datetimepicker4" class="input-append">



<input name="FECHAINGRESOJUSFAPROD" type="text" id="FECHAINGRESOJUSFAPROD" data-format="yyyy-MM-dd"></input>

<span class="add-on"><script type="text/javascript"

src="http://cdnjs.cloudflare.com/ajax/libs/jquery/1.8.3/jquery.min.js">

</script>

<script type="text/javascript"

src="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.2.2/js/bootstrap.min.js">

</script>

<script type="text/javascript"

src="http://tarruda.github.com/bootstrap-datetimepicker/assets/js/bootstrap-datetimepicker.min.js">

</script>

<script type="text/javascript"

src="http://tarruda.github.com/bootstrap-datetimepicker/assets/js/bootstrap-datetimepicker.pt-BR.js">

</script> <div id="datetimepicker4" class="input-append">


<i data-time-icon="icon-time" data-date-icon="icon-calendar">

</i>



</div>

<script type="text/javascript">

$(function() {

$('#datetimepicker4').datetimepicker({

pickTime: false

});

});
</script></td>
        
            <td>&nbsp;</td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right"><p>Justificación</p></td>
            <td><span id="sprytextfield2">
              <label for="text1"></label>
              <textarea name="text1" id="text1"></textarea>
            <span class="textfieldRequiredMsg">Se necesita un valor.</span></span></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">&nbsp;</td>
            <td><input type="submit" value="Insertar registro" /></td>
          </tr>
        </table>
        <input type="hidden" name="MM_insert" value="form2" />
      </form>
    <p>&nbsp;</p></td>
  </tr>
</table>
<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "none", {validateOn:["blur"]});
</script>
</body>
</html>
<?php
mysql_free_result($justifi);

mysql_free_result($control);

mysql_free_result($producto);

mysql_free_result($medidas);
?>
