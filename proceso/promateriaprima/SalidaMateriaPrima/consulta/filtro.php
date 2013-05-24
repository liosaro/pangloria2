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

$maxRows_consulta = 10;
$pageNum_consulta = 0;
if (isset($_GET['pageNum_consulta'])) {
  $pageNum_consulta = $_GET['pageNum_consulta'];
}
$startRow_consulta = $pageNum_consulta * $maxRows_consulta;
mysql_select_db($database_basepangloria, $basepangloria);

 if (isset($_GET['fechai']) && isset($_GET['fechaf'])) {

        $fechainicio = '"' . $_GET['fechai'] . '"';

        $fechafin = '"' . $_GET['fechaf'] . '"';

        $row_consulta = "SELECT * FROM TRNENCABEZADOSALIDMATPRIMA WHERE FECHAYHORASALIDAMATPRIMA between $fechainicio and $fechafin and ELIMIN = 0 ORDER BY FECHAYHORASALIDAMATPRIMA DESC";

        $result_buscar = mysql_query($row_consulta);

        if (!$result_buscar) {
			echo "$fechafin";
			echo "$fechainicio";
            echo "No pudo ejecutarse satisfactoriamente la consulta ($sql) " .
            "en la BD: " . mysql_error();
            //Finalizo la aplicación
            exit;
        }
 }

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<link href="../../../../css/forms.css" rel="stylesheet" type="text/css" />
<link href="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.2.2/css/bootstrap-combined.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" media="screen" href="http://tarruda.github.com/bootstrap-datetimepicker/assets/css/bootstrap-datetimepicker.min.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../../../../SpryAssets/bootstrap-combined.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" media="screen"
     href="../../../../css/bootstrap-datetimepicker.min.css">
</head>

<body>
<table width="820" border="0">
  <tr>
    <td>
    <div class="input-prepend">
        <h5 style="margin-left: 15px ;">Filtrar por Fecha <span class="etifactu">Inicial:</span><?php echo $fechainicio ?><span class="etifactu">Final:</span> <?php echo $fechafin ?>
        <p style="margin-left: 15px ;">&nbsp;</p>
        <table width="100%" border="0">
          <tr>
            <td width="15%">Fecha Inicio</td>
            <td width="69%"><script type="text/javascript"
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
    </script>  <div id="datetimepicker4" class="input-append">
     <input name="fechai"  id="fechai"data-format="yyyy-MM-dd HH:mm:ss" type="text"></input>
    <span class="add-on">
      <i data-time-icon="icon-time" data-date-icon="icon-calendar">
      </i>
    </span>
  </div>
<script type="text/javascript">
  $(function() {
    $('#datetimepicker4').datetimepicker({
      pickTime: true,
	  language: 'es'
    });
  });
</script></td>
            <td width="8%">&nbsp;</td>
            <td width="8%"><script type="text/javascript">
  $(function() {
    $('#datetimepicker2').datetimepicker({
      language: 'es',
      pick12HourFormat: true
    });
  });
</script></td>
          </tr>
          <tr>
            <td>Fecha Fin</td>
            <td><div>
              <div id="datetimepicker2" class="input-append">
                <input name="fechaf"  id="fechaf"data-format="yyyy-MM-dd HH:mm:ss" type="text" />
                <span class="add-on"> <i data-time-icon="icon-time" data-date-icon="icon-calendar"> </i> </span> </div>
            </div></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
        </table>
<button style="margin-left: 15px ;" class="btn btn-warning" onclick="window.open('?fechai='+fechai.value+'&fechaf='+fechaf.value+'','_self');">Buscar</button>       
      </div>
    </td>
  </tr>
</table>
<table width="820" border="1" cellpadding="0" cellspacing="0">
  <tr>
    <td>Salida de Materia Prima No.</td>
    <td>Pedido de Materia Prima No.</td>
    <td>Fecha</td>
     <td>consultar</td>
  </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $result['IDENCABEZADOSALMATPRI']; ?></td>
      <td><?php echo $result['ID_PED_MAT_PRIMA']; ?></td>
      <td><?php echo $result['FECHAYHORASALIDAMATPRIMA']; ?></td>
      <td align="center"><a href="consulta.php?enca=<?php echo $result['IDENCABEZADOSALMATPRI']; ?>" target="_self"><img src="../../../../imagenes/icono/Invoice-256.png" width="32" height="32" onclick="" /></a></td>
    </tr>
    <?php } while ($result = mysql_fetch_assoc($result_buscar)); ?>
</table>
</body>
</html>
<?php
mysql_free_result($consulta);
?>
