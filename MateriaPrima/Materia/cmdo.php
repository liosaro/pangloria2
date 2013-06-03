<?php require_once('../../Connections/basepangloria.php'); ?>
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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE CATMATERIAPRIMA SET IDTIPO=%s, DESCRIPCION=%s, UBICACIONBODEGA=%s WHERE IDMATPRIMA=%s",
                       GetSQLValueString($_POST['IDTIPO'], "text"),
                       GetSQLValueString($_POST['DESCRIPCION'], "text"),
                       GetSQLValueString($_POST['UBICACIONBODEGA'], "text"),
                       GetSQLValueString($_POST['IDMATPRIMA'], "int"));

  mysql_select_db($database_basepangloria, $basepangloria);
  $Result1 = mysql_query($updateSQL, $basepangloria) or die(mysql_error());

  $updateGoTo = "modicacionmate.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_modi = "-1";
if (isset($_GET['root'])) {
  $colname_modi = $_GET['root'];
}
mysql_select_db($database_basepangloria, $basepangloria);
$query_modi = sprintf("SELECT * FROM CATMATERIAPRIMA WHERE IDMATPRIMA = %s", GetSQLValueString($colname_modi, "int"));
$modi = mysql_query($query_modi, $basepangloria) or die(mysql_error());
$row_modi = mysql_fetch_assoc($modi);
$totalRows_modi = mysql_num_rows($modi);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
</head>

<body>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center">
    <tr valign="baseline">
      <td colspan="2" align="center" nowrap="nowrap" bgcolor="#999999"><h2>Modificacion Materia Prima</h2></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Id Materia Prima:</td>
      <td><?php echo $row_modi['IDMATPRIMA']; ?></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Tipo de Materia Prima:</td>
      <td><input type="text" name="IDTIPO" value="<?php echo htmlentities($row_modi['IDTIPO'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Descripcion:</td>
      <td><input type="text" name="DESCRIPCION" value="<?php echo htmlentities($row_modi['DESCRIPCION'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Ubicacion de Materia Prima:</td>
      <td><input type="text" name="UBICACIONBODEGA" value="<?php echo htmlentities($row_modi['UBICACIONBODEGA'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Actualizar registro" /></td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="IDMATPRIMA" value="<?php echo $row_modi['IDMATPRIMA']; ?>" />
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($modi);
?>
