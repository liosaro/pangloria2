<head> 
<?php require_once('../../../../Connections/basepangloria.php'); 
mysql_select_db($database_basepangloria, $basepangloria);
?>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
<title>Documento sin título</title> 

<?php  
// consulto  el ultimo numero de orden emitida, en este caso el encabezado, y lo ordeno de forma desendente

$row_ulencado = $_GET['enca'];
//verificamos que se marcara al menos 1 checkbox 
echo '<pre>';
echo '<p>Se guardaron los siguientes registros:</p>';
echo '<p>__________________________________________________</p>';
if (isset($_POST['very'])) { 
     foreach($_POST['very'] as $idMatPrima ) { 
	 			$sql1="SELECT IDMATPRIMA, IDORDEN, IDUNIDAD, CANTPRODUCTO, PRECIOUNITARIO FROM TRNDETALLEORDENCOMPRA WHERE IDDETALLECOMP = '$idMatPrima'";
				$rs1=mysql_query($sql1);
				$fill = mysql_fetch_array($rs1);
				echo '<p>No. de Compra: '.$row_ulencado.'</p>';
			   echo '<p>Detalle de Orden de  Compra: '.$idMatPrima.'</p>';
			   echo '<p>Materia Prima: '.$fill['IDMATPRIMA'].'</p>';
			   echo '<p>Orden de Produccion: '.$fill['IDORDEN'].'</p>';
			   echo '<p>Unidad de Media: '.$fill['IDUNIDAD'].'</p>';
			   echo '<p>Cantidad de Producto: '.$fill['CANTPRODUCTO'].'</p>';
			   echo '<p>Precio Unitario: '.$fill['PRECIOUNITARIO'].'</p>';
			   echo '<p>+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++</p>';
			   mysql_query("INSERT INTO TRNDETALLECOMPRA (ID_DETENCCOM, MATERIAPRIMA,  IDUNIDAD, CANTIDADMATPRIMA, PRECIOUNIDAD) VALUES ('".$row_ulencado."','".$fill['IDMATPRIMA']."','".$fill['IDUNIDAD']."','".$fill['CANTPRODUCTO']."','".$fill['PRECIOUNITARIO']."' )") or die(mysql_error());
			   
     } 
	 }


function urlActual() {
 $pageURL = 'http://';
 if ($_SERVER["SERVER_PORT"] != "80") {
 $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
 $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
 }
$url = $_SERVER['HTTP_REFERER'];
echo $url;
header ("location: $url ");?>
</head> 


 
<body> 
</body> 
</html>