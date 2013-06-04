<head>
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

<?php require_once('../../../Connections/basepangloria.php'); ?>
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

$maxRows_eliminarSucur = 10;
$pageNum_eliminarSucur = 0;
if (isset($_GET['pageNum_eliminarSucur'])) {
  $pageNum_eliminarSucur = $_GET['pageNum_eliminarSucur'];
}
$startRow_eliminarSucur = $pageNum_eliminarSucur * $maxRows_eliminarSucur;

mysql_select_db($database_basepangloria, $basepangloria);
$query_eliminarSucur = "SELECT * FROM CATSUCURSAL WHERE ELIMIN=0 and EDITA =0 ORDER BY IDSUCURSAL DESC";
$query_limit_eliminarSucur = sprintf("%s LIMIT %d, %d", $query_eliminarSucur, $startRow_eliminarSucur, $maxRows_eliminarSucur);
$eliminarSucur = mysql_query($query_limit_eliminarSucur, $basepangloria) or die(mysql_error());
$row_eliminarSucur = mysql_fetch_assoc($eliminarSucur);

if (isset($_GET['totalRows_eliminarSucur'])) {
  $totalRows_eliminarSucur = $_GET['totalRows_eliminarSucur'];
} else {
  $all_eliminarSucur = mysql_query($query_eliminarSucur);
  $totalRows_eliminarSucur = mysql_num_rows($all_eliminarSucur);
}
$totalPages_eliminarSucur = ceil($totalRows_eliminarSucur/$maxRows_eliminarSucur)-1;
?>
<table width="820" border="0">
  <tr>
    <td align="center" bgcolor="#999999"><h1>Eliminar Sucursal</h1></td>
  </tr>
  <tr>
    <td><iframe src="filtroelimiSucur.php" name="conten" width="820" height="400" scrolling="auto"></iframe>&nbsp;</td>
  </tr>
  <tr>
    <td><img src="../../../imagenes/icono/Back-32.png" width="32" height="32" /><img src="../../../imagenes/icono/Backward-32.png" width="32" height="32" /><img src="../../../imagenes/icono/Forward-32.png" width="32" height="32" /><img src="../../../imagenes/icono/Next-32.png" width="32" height="32" /></td>
  </tr>
  <tr>
    <td><form action="filtroelimiSucur.php" method="post" name="form1" target="conten" id="form1">
      <label for="filtrosucu"></label>
      Ingrese el nombre de la Sucursal a Eliminar
      <input type="text" name="filtrosucu" id="filtrosucu" />
      <input type="submit" name="button" id="button" value="Enviar" />
    </form></td>
  </tr>
  <tr>
    <td>&nbsp;
      <table border="1">
        <tr class="retabla">
          <td align="center" bgcolor="#000000">Eliminación</td>
          <td align="center" bgcolor="#000000">Código</td>
          <td align="center" bgcolor="#000000">Sucursal</td>
          <td align="center" bgcolor="#000000">Dirección</td>
          <td align="center" bgcolor="#000000">Teléfono</td>
        </tr>
        <?php do { ?>
          <tr>
            <td><a href="javascript:;" onclick="aviso('eliminarSucur.php?root=<?php echo $row_eliminarSucur['IDSUCURSAL']; ?>'); return false;"><img src="../../../imagenes/icono/delete-32.png" width="32" height="32" /></a></td>
            <td><?php echo $row_eliminarSucur['IDSUCURSAL']; ?></td>
            <td><?php echo $row_eliminarSucur['NOMBRESUCURSAL']; ?></td>
            <td><?php echo $row_eliminarSucur['DIRECCIONSUCURSAL']; ?></td>
            <td><?php echo $row_eliminarSucur['TELEFONOSUCURSAL']; ?></td>
          </tr>
          <?php } while ($row_eliminarSucur = mysql_fetch_assoc($eliminarSucur)); ?>
    </table></td>
  </tr>
</table>


<?php
mysql_free_result($eliminarSucur);
?>
