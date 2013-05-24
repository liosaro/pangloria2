<head> 
<?php require_once('../../../../Connections/basepangloria.php'); 
mysql_select_db($database_basepangloria, $basepangloria);
?>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
<title>Documento sin título</title> 

<?php  
// consulto la el ultimo numero de orden emitida, en este caso el encabezado, y lo ordeno de forma desendente
$query_ulencado = "SELECT IDENCABEZADOSALMATPRI FROM TRNENCABEZADOSALIDMATPRIMA ORDER BY IDENCABEZADOSALMATPRI DESC";
$ulencado = mysql_query($query_ulencado, $basepangloria) or die(mysql_error());
$row_ulencado = mysql_fetch_assoc($ulencado);
//verificamos que se marcara al menos 1 checkbox 
echo '<pre>';
echo '<p>Se guardaron los siguientes registros:</p>';
echo '<p>__________________________________________________</p>';
if (isset($_POST['very'])) { 
     foreach($_POST['very'] as $idMatPrima) { 
	 			$sql1="SELECT IDUNIDAD, IDMATPRIMA, CANTIDADPEDMATPRI,ID_PED_MAT_PRIMA FROM TRNPEDIDO_MAT_PRIMA WHERE ID_PED_MAT_PRIMA = '$idMatPrima'";
				$rs1=mysql_query($sql1);
				$fill = mysql_fetch_array($rs1);
			   echo '<p>Detalle de Entrada: '.$idMatPrima.'</p>';
			   echo '<p>Encabezado de Salida de Materia Prima: '.$row_ulencado['IDENCABEZADOSALMATPRI'].'</p>';
			   echo '<p>Canitdad de Producto: '.$fill['CANTIDADPEDMATPRI'].'</p>';
			   echo '<p>Materia Prima: '.$fill['IDMATPRIMA'].'</p>';
			   echo '<p>Unidad de Medida: '.$fill['IDUNIDAD'].'</p>';
			   echo '<p>Canidad: '.$fill['CANTPRODUCTO'].'</p>';
			   echo '<p>+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++</p>';
     } 
}?> 
</head> 
 
<body> 
</body> 
</html>