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
  $insertSQL = sprintf("INSERT INTO TRNCONTROL_MAT_PRIMA (ID_CONTROLMAT, IDMATPRIMA, ID_SALIDA, IDUNIDAD, CANT_ENTREGA, CANT_DEVUELTA, CANT_UTILIZADA, FECHA_CONTROL, ELIMIN, EDITA) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['ID_CONTROLMAT'], "int"),
                       GetSQLValueString($_POST['IDMATPRIMA'], "int"),
                       GetSQLValueString($_POST['ID_SALIDA'], "int"),
                       GetSQLValueString($_POST['IDUNIDAD'], "int"),
                       GetSQLValueString($_POST['CANT_ENTREGA'], "double"),
                       GetSQLValueString($_POST['CANT_DEVUELTA'], "double"),
                       GetSQLValueString($_POST['CANT_UTILIZADA'], "double"),
                       GetSQLValueString($_POST['FECHA_CONTROL'], "date"),
                       GetSQLValueString($_POST['ELIMIN'], "int"),
                       GetSQLValueString($_POST['EDITA'], "int"));

  mysql_select_db($database_basepangloria, $basepangloria);
  $Result1 = mysql_query($insertSQL, $basepangloria) or die(mysql_error());
}

mysql_select_db($database_basepangloria, $basepangloria);
$query_unidadmediad = "SELECT IDUNIDAD, TIPOUNIDAD FROM CATUNIDADES WHERE ELIMIN = ELIMIN ORDER BY TIPOUNIDAD ASC";
$unidadmediad = mysql_query($query_unidadmediad, $basepangloria) or die(mysql_error());
$row_unidadmediad = mysql_fetch_assoc($unidadmediad);
$totalRows_unidadmediad = mysql_num_rows($unidadmediad);

mysql_select_db($database_basepangloria, $basepangloria);
$query_MateriaPRima = "SELECT IDMATPRIMA, DESCRIPCION FROM CATMATERIAPRIMA WHERE ELIMIN = 0 ORDER BY DESCRIPCION ASC";
$MateriaPRima = mysql_query($query_MateriaPRima, $basepangloria) or die(mysql_error());
$row_MateriaPRima = mysql_fetch_assoc($MateriaPRima);
$totalRows_MateriaPRima = mysql_num_rows($MateriaPRima);

mysql_select_db($database_basepangloria, $basepangloria);
$query_ultima = "SELECT IDENCABEZADOSALMATPRI FROM TRNENCABEZADOSALIDMATPRIMA WHERE ELIMIN = 0 ORDER BY IDENCABEZADOSALMATPRI DESC";
$ultima = mysql_query($query_ultima, $basepangloria) or die(mysql_error());
$row_ultima = mysql_fetch_assoc($ultima);
$totalRows_ultima = mysql_num_rows($ultima);

mysql_select_db($database_basepangloria, $basepangloria);
$query_Ultimocontrol = "SELECT ID_CONTROLMAT FROM TRNCONTROL_MAT_PRIMA ORDER BY ID_CONTROLMAT DESC";
$Ultimocontrol = mysql_query($query_Ultimocontrol, $basepangloria) or die(mysql_error());
$row_Ultimocontrol = mysql_fetch_assoc($Ultimocontrol);
$totalRows_Ultimocontrol = mysql_num_rows($Ultimocontrol);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<link href="../../../../css/forms.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="left">
    <tr valign="baseline">
      <td colspan="2" align="right" nowrap="nowrap" bgcolor="#999999" class="encaforms">Ingreso Control de Materia Prima</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">No. de Control:</td>
      <td><input type="text" name="ID_CONTROLMAT" value="<?php echo $row_Ultimocontrol['ID_CONTROLMAT']; ?>" size="9" />
      <input name="EDITA" type="text" disabled="disabled" value="0" size="1" />
      <input name="ELIMIN" type="text" disabled="disabled" value="0" size="1" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Materia Prima</td>
      <td><select name="IDMATPRIMA">
        <?php 
do {  
?>
        <option value="<?php echo $row_MateriaPRima['IDMATPRIMA']?>" ><?php echo $row_MateriaPRima['DESCRIPCION']?></option>
        <?php
} while ($row_MateriaPRima = mysql_fetch_assoc($MateriaPRima));
?>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">No. de Salida</td>
      <td><select name="ID_SALIDA">
        <?php 
do {  
?>
        <option value="<?php echo $row_ultima['IDENCABEZADOSALMATPRI']?>" ><?php echo $row_ultima['IDENCABEZADOSALMATPRI']?></option>
        <?php
} while ($row_ultima = mysql_fetch_assoc($ultima));
?>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Unidad de Medida:</td>
      <td><select name="IDUNIDAD">
        <?php 
do {  
?>
        <option value="<?php echo $row_unidadmediad['IDUNIDAD']?>" ><?php echo $row_unidadmediad['TIPOUNIDAD']?></option>
        <?php
} while ($row_unidadmediad = mysql_fetch_assoc($unidadmediad));
?>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Cantidad Entregada:</td>
      <td><input type="text" name="CANT_ENTREGA" value="" size="9" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Cantidad devuelta:</td>
      <td><input type="text" name="CANT_DEVUELTA" value="" size="9" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Cantidad Usada:</td>
      <td><input type="text" name="CANT_UTILIZADA" value="" size="9" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Fecha del control:</td>
      <td><input type="text" name="FECHA_CONTROL" value="" size="9" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Insertar registro" /></td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form1" />
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($unidadmediad);

mysql_free_result($MateriaPRima);

mysql_free_result($ultima);

mysql_free_result($Ultimocontrol);
?>
