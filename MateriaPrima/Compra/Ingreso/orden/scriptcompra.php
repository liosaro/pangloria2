<head> 
<?php require_once('../../../../Connections/basepangloria.php'); 
mysql_select_db($database_basepangloria, $basepangloria);
?>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
<title>Documento sin título</title> 

<?php  
// consulto  el ultimo numero de orden emitida, en este caso el encabezado, y lo ordeno de forma desendente
$query_ulencado = "SELECT ID_DETENCCOM FROM TRNENCABEZADOCOMPRA ORDER BY ID_DETENCCOM DESC";
$ulencado = mysql_query($query_ulencado, $basepangloria) or die(mysql_error());
$row_ulencado = mysql_fetch_assoc($ulencado);
//verificamos que se marcara al menos 1 checkbox 
echo '<pre>';
echo '<p>Se guardaron los siguientes registros:</p>';
echo '<p>__________________________________________________</p>';
if (isset($_POST['very'])) { 
     foreach($_POST['very'] as $idMatPrima ) { 
	 			$sql1="SELECT IDMATPRIMA, IDORDEN, IDUNIDAD, CANTPRODUCTO, PRECIOUNITARIO FROM TRNDETALLEORDENCOMPRA WHERE IDDETALLECOMP = '$idMatPrima'";
				$rs1=mysql_query($sql1);
				$fill = mysql_fetch_array($rs1);
				echo '<p>No. de Compra: '.$row_ulencado['ID_DETENCCOM'].'</p>';
			   echo '<p>Detalle de Orden de  Compra: '.$idMatPrima.'</p>';
			   echo '<p>Materia Prima: '.$fill['IDMATPRIMA'].'</p>';
			   echo '<p>Orden de Produccion: '.$fill['IDORDEN'].'</p>';
			   echo '<p>Unidad de Media: '.$fill['IDUNIDAD'].'</p>';
			   echo '<p>Cantidad de Producto: '.$fill['CANTPRODUCTO'].'</p>';
			   echo '<p>Precio Unitario: '.$fill['PRECIOUNITARIO'].'</p>';
			   echo '<p>+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++</p>';
			   mysql_query("INSERT INTO TRNDETALLECOMPRA (ID_DETENCCOM, MATERIAPRIMA,  IDUNIDAD, CANTIDADMATPRIMA, PRECIOUNIDAD) VALUES ('".$row_ulencado['ID_DETENCCOM']."','".$fill['IDMATPRIMA']."','".$fill['IDUNIDAD']."','".$fill['CANTPRODUCTO']."','".$fill['PRECIOUNITARIO']."' )") or die(mysql_error());
			   
     } 
	 }

?> 
</head> 
 
<body> 
</body> 
</html>