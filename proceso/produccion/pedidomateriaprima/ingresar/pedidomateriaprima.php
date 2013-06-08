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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO TRNENCABEZADOPEDMATPRI (ID_ENCAPEDIDO, IDEMPLEADO, IDORDENPRODUCCION, FECHA, USUARIO, FECHAHORAUSUA) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['ID_ENCAPEDIDO'], "int"),
                       GetSQLValueString($_POST['IDEMPLEADO'], "int"),
                       GetSQLValueString($_POST['IDORDENPRODUCCION'], "int"),
                       GetSQLValueString($_POST['FECHA'], "date"),
                       GetSQLValueString($_POST['USUARIO'], "int"),
                       GetSQLValueString($_POST['FECHAHORAUSUA'], "date"));
$ord = $_POST['IDORDENPRODUCCION'];
$updateSQL = sprintf("UPDATE TRNENCABEZADOORDENPROD SET EDITA=1 WHERE IDENCABEORDPROD= $ord");

  mysql_select_db($database_basepangloria, $basepangloria);
  $Result1 = mysql_query($insertSQL, $basepangloria) or die(mysql_error());
  $Result2 = mysql_query($updateSQL, $basepangloria) or die(mysql_error());
  
}

mysql_select_db($database_basepangloria, $basepangloria);
$query_combordenprod = "SELECT IDENCABEORDPROD FROM TRNENCABEZADOORDENPROD";
$combordenprod = mysql_query($query_combordenprod, $basepangloria) or die(mysql_error());
$row_combordenprod = mysql_fetch_assoc($combordenprod);
$totalRows_combordenprod = mysql_num_rows($combordenprod);$colname_combordenprod = "-1";
if (isset($_GET['0'])) {
  $colname_combordenprod = $_GET['0'];
}
mysql_select_db($database_basepangloria, $basepangloria);
$query_combordenprod = sprintf("SELECT IDENCABEORDPROD FROM TRNENCABEZADOORDENPROD WHERE ELIMIN = %s ORDER BY IDENCABEORDPROD DESC", GetSQLValueString($colname_combordenprod, "int"));
$combordenprod = mysql_query($query_combordenprod, $basepangloria) or die(mysql_error());
$row_combordenprod = mysql_fetch_assoc($combordenprod);
$totalRows_combordenprod = mysql_num_rows($combordenprod);
$query_combordenprod = "SELECT IDENCABEORDPROD FROM TRNENCABEZADOORDENPROD ORDER BY IDENCABEORDPROD DESC";
$combordenprod = mysql_query($query_combordenprod, $basepangloria) or die(mysql_error());
$row_combordenprod = mysql_fetch_assoc($combordenprod);
$totalRows_combordenprod = mysql_num_rows($combordenprod);

mysql_select_db($database_basepangloria, $basepangloria);
$query_comempleado = "SELECT IDEMPLEADO, NOMBREEMPLEADO FROM CATEMPLEADO";
$comempleado = mysql_query($query_comempleado, $basepangloria) or die(mysql_error());
$row_comempleado = mysql_fetch_assoc($comempleado);
$totalRows_comempleado = mysql_num_rows($comempleado);

mysql_select_db($database_basepangloria, $basepangloria);
$query_ultimoped = "SELECT ID_ENCAPEDIDO FROM TRNENCABEZADOPEDMATPRI ORDER BY ID_ENCAPEDIDO DESC";
$ultimoped = mysql_query($query_ultimoped, $basepangloria) or die(mysql_error());
$row_ultimoped = mysql_fetch_assoc($ultimoped);
$totalRows_ultimoped = mysql_num_rows($ultimoped);
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
	margin-right: 0px;
	margin-bottom: 0px;
}
</style>
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
<table width="820" border="0">
  <tr>
    <td><form action="<?php echo $editFormAction; ?> " method="post" name="form1" id="form1">
        <table width="100%" border="0">
          <tr>
            <td colspan="4" align="center" bgcolor="#999999"><h1>Ingreso Pedido de Materia Prima</h1></td>
          </tr>
          <tr>
            <td>Id Encabezado de Pedido:</td>
            <td><input name="ID_ENCAPEDIDO" type="text" disabled="disabled" value="<?php echo $row_ultimoped['ID_ENCAPEDIDO']+1;?>" size="32" readonly="readonly" /></td>
            <td>Empleado que Pide:</td>
            <td><select name="IDEMPLEADO" onchange="document.form1.enviarenca.disabled=false;">
              <?php
do {  
?>
              <option value="<?php echo $row_comempleado['IDEMPLEADO']?>"><?php echo $row_comempleado['NOMBREEMPLEADO']?></option>
              <?php
} while ($row_comempleado = mysql_fetch_assoc($comempleado));
  $rows = mysql_num_rows($comempleado);
  if($rows > 0) {
      mysql_data_seek($comempleado, 0);
	  $row_comempleado = mysql_fetch_assoc($comempleado);
  }
?>
            </select></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>Fecha:</td>
            <td><div id="datetimepicker4" class="input-append">
              <input name="FECHA" type="text" id="FECHA" data-format="yyyy-MM-dd"  onchange="document.form1.enviarenca.disabled=false;" />
              </input>
            <span class="add-on"> <i data-time-icon="icon-time" data-date-icon="icon-calendar"> </i> </span> </div></td>
            <td>Elija la Orden de Producción:</td>
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
    </script><script type="text/javascript">
  $(function() {
    $('#datetimepicker4').datetimepicker({
      pickTime: false
    });
  });
</script>
    <select name="IDORDENPRODUCCION" onchange="document.form1.enviarenca.disabled=false;">
      <?php
do {  
?>
      <option value="<?php echo $row_combordenprod['IDENCABEORDPROD']?>"><?php echo $row_combordenprod['IDENCABEORDPROD']?></option>
      <?php
} while ($row_combordenprod = mysql_fetch_assoc($combordenprod));
  $rows = mysql_num_rows($combordenprod);
  if($rows > 0) {
      mysql_data_seek($combordenprod, 0);
	  $row_combordenprod = mysql_fetch_assoc($combordenprod);
  }
?>
    </select></td>
          </tr>
          <tr>
            <td><input type="submit" name="enviarenca" id="enviarenca" value="Insertar Encabezado" disabled/></td>
            <td><input name="CERADOR" type="submit" class="label-important" id="CERADOR" value="Cerrar" onclick="cerrarse()"/></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td colspan="4">&nbsp;</td>
          </tr>
        </table>
          <input type="hidden" name="MM_insert" value="form1" />
      </form>  
      </tr>
</table>
    <p>&nbsp;</p></td>
</body>
</html>
<?php
mysql_free_result($combordenprod);

mysql_free_result($comempleado);

mysql_free_result($ultimoped);
?>
