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
  $insertSQL = sprintf("INSERT INTO TRNENCABEZADOJUSTPERMATPRIM (IDENCABEZADO, IDEMPLEADO, IDORDENPRODUCCION, FECHAINGRESOJUSTIFICA, EMPLEADOINGRESA, FECHAHORAUSUA) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['IDENCABEZADO'], "int"),
                       GetSQLValueString($_POST['IDEMPLEADO'], "int"),
                       GetSQLValueString($_POST['IDORDENPRODUCCION'], "int"),
                       GetSQLValueString($_POST['FECHAINGRESOJUSTIFICA'], "date"),
                       GetSQLValueString($_POST['EMPLEADOINGRESA'], "int"),
                       GetSQLValueString($_POST['FECHAHORAUSUA'], "date"));

  mysql_select_db($database_basepangloria, $basepangloria);
  $Result1 = mysql_query($insertSQL, $basepangloria) or die(mysql_error());
}

mysql_select_db($database_basepangloria, $basepangloria);
$query_comboempleado = "SELECT IDEMPLEADO, NOMBREEMPLEADO FROM CATEMPLEADO";
$comboempleado = mysql_query($query_comboempleado, $basepangloria) or die(mysql_error());
$row_comboempleado = mysql_fetch_assoc($comboempleado);
$totalRows_comboempleado = mysql_num_rows($comboempleado);

mysql_select_db($database_basepangloria, $basepangloria);
$query_comboorden = "SELECT IDENCABEORDPROD FROM TRNENCABEZADOORDENPROD ORDER BY IDENCABEORDPROD DESC";
$comboorden = mysql_query($query_comboorden, $basepangloria) or die(mysql_error());
$row_comboorden = mysql_fetch_assoc($comboorden);
$totalRows_comboorden = mysql_num_rows($comboorden);

mysql_select_db($database_basepangloria, $basepangloria);
$query_ultimajusti = "SELECT IDENCABEZADO FROM TRNENCABEZADOJUSTPERMATPRIM ORDER BY IDENCABEZADO DESC";
$ultimajusti = mysql_query($query_ultimajusti, $basepangloria) or die(mysql_error());
$row_ultimajusti = mysql_fetch_assoc($ultimajusti);
$totalRows_ultimajusti = mysql_num_rows($ultimajusti);

$colname_usuarios = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuarios = $_SESSION['MM_Username'];
}
mysql_select_db($database_basepangloria, $basepangloria);
$query_usuarios = sprintf("SELECT IDUSUARIO FROM CATUSUARIO WHERE NOMBREUSUARIO = %s", GetSQLValueString($colname_usuarios, "text"));
$usuarios = mysql_query($query_usuarios, $basepangloria) or die(mysql_error());
$row_usuarios = mysql_fetch_assoc($usuarios);
$totalRows_usuarios = mysql_num_rows($usuarios);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<link href="../../../../SpryAssets/bootstrap-combined.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" media="screen"
     href="../../../../css/bootstrap-datetimepicker.min.css">
     <script>
function cerrarse()
{
 opener.location.reload();
 window.close()
}
</script>
</head>

<body>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table width="820" border="0">
    <tr>
      <td colspan="4" align="center" bgcolor="#999999"><h1>Justificacion de <span class="well-small">Perdida</span> de Materia Prima</h1></td>
    </tr>
    <tr>
      <td width="213">Codigo de Justificacion:</td>
      <td width="215"><input name="IDENCABEZADOR" type="text" disabled="disabled" id="IDENCABEZADOR" value="<?php echo $row_ultimajusti['IDENCABEZADO']+1; ?>" size="32" readonly="readonly" /></td>
      <td width="154">Empleado que Justifica:</td>
      <td width="220"><select name="IDEMPLEADO">
        <?php
do {  
?>
        <option value="<?php echo $row_comboempleado['IDEMPLEADO']?>"><?php echo $row_comboempleado['NOMBREEMPLEADO']?></option>
        <?php
} while ($row_comboempleado = mysql_fetch_assoc($comboempleado));
  $rows = mysql_num_rows($comboempleado);
  if($rows > 0) {
      mysql_data_seek($comboempleado, 0);
	  $row_comboempleado = mysql_fetch_assoc($comboempleado);
  }
?>
      </select></td>
    </tr>
    <tr>
      <td>Fecha de Ingreso</td>
      <td><script type="text/javascript"
      src="../../../../SpryAssets/jquery-1.8.3.min.js">
    </script> 
    <script type="text/javascript"
      src="../../../../SpryAssets/bootstrap.min.js">
    </script>
    <script type="text/javascript"
      src="../../../../SpryAssets/bootstrap-datetimepicker.min.js">
    </script>
    <script type="text/javascript"
     src="../../../../SpryAssets/bootstrap-datetimepicker.es.js">
    </script>  
        <div class="welsl">
          <div id="datetimepicker4" class="input-append">
            <input data-format="yyyy-MM-dd" type="text" name="FECHAINGRESOJUSTIFICA" />
            </input>
            <span class="add-on"> <i data-time-icon="icon-time" data-date-icon="icon-calendar"> </i> </span> </div>
        </div>
        <script type="text/javascript">
  $(function() {
    $('#datetimepicker4').datetimepicker({
      pickTime: false
    });
  });
  </script></td>
      <td>Orden de Produccion:</td>
      <td><p>
        <select name="IDORDENPRODUCCION">
          <?php
do {  
?>
          <option value="<?php echo $row_comboorden['IDENCABEORDPROD']?>"><?php echo $row_comboorden['IDENCABEORDPROD']?></option>
          <?php
} while ($row_comboorden = mysql_fetch_assoc($comboorden));
  $rows = mysql_num_rows($comboorden);
  if($rows > 0) {
      mysql_data_seek($comboorden, 0);
	  $row_comboorden = mysql_fetch_assoc($comboorden);
  }
?>
        </select>
      </p></td>
    </tr>
    <tr>
      <td><input type="submit" value="Insertar Encabezado" /></td>
      <td>&nbsp;</td>
      <td>Codigo de Usuario:</td>
      <td><input name="EMPLEADOINGRESA" type="text" id="EMPLEADOINGRESA" value="<?php echo $row_usuarios['IDUSUARIO']; ?>" readonly="readonly" /></td>
    </tr>
    <tr>
      <td><input name="CERADOR" type="submit" class="label-warning" id="CERADOR" value="Cerrar" onclick="cerrarse()"/></td>
      <td>&nbsp;</td>
      <td>Fecha de registro:</td>
      <td><input name="FECHAHORAUSUA" type="text" id="FECHAHORAUSUA" value="<?php echo date("Y-m-d H:i:s");;?> " readonly="readonly" /></td>
    </tr>
    <tr>
      <td colspan="4">&nbsp;</td>
    </tr>
  </table>
  <p>
    <input type="hidden" name="MM_insert" value="form1" />
  </p>
</form>
</body>
</html>
<?php
mysql_free_result($comboempleado);

mysql_free_result($comboorden);
?>
