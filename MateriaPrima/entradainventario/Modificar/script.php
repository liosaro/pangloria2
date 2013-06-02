<head> 
<?php require_once('../../../Connections/basepangloria.php'); 
mysql_select_db($database_basepangloria, $basepangloria);
?>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
<title>Documento sin título</title> 

<?php  
//verificamos que se marcara al menos 1 checkbox 
echo '<pre>';
echo '<p>Se actualizaron los siguientes registros:</p>';
echo '<p>__________________________________________________</p>';
if (isset($_POST['very'])) { 
     foreach($_POST['very'] as $idMatPrima) { 
	 			$enca= $_GET['ENCA'];
	 			$sql1="SELECT IDCOMPRA, IDUNIDAD, ID_DETENCCOM, CANTIDADMATPRIMA, MATERIAPRIMA FROM TRNDETALLECOMPRA WHERE IDCOMPRA = '$idMatPrima'";
				$rs1=mysql_query($sql1);
				$fill = mysql_fetch_array($rs1);
				$mat= $fill['MATERIAPRIMA'];
			   $sql2="Select CantDisponible from CATMATERIAPRIMA where IDMATPRIMA= $mat ";
			   $rsl2=mysql_query($sql2);
			   $fil2 = mysql_fetch_array($rsl2);
			   $nueva= $fil2['CantDisponible'] + $fill['CANTIDADMATPRIMA'];
			   echo '<p>Detalle de Entrada: '.$idMatPrima.'</p>';
			   echo '<p>No. entrada Inventario: '.$enca.'</p>';
			   echo '<p>Canitdad de Producto: '.$fill['CANTIDADMATPRIMA'].'</p>';
			   echo '<p>Materia Prima: '.$fill['MATERIAPRIMA'].'</p>';
			   echo '<p>Unidad de Medida: '.$fill['IDUNIDAD'].'</p>';
			   echo  '<p>Ultima Cantidad Disponible: '.$fil2['CantDisponible'].'</p>';
			   echo  '<p>Nueva Cantidad Disponible: '.$nueva.'</p>';
			     echo '<p>+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++</p>';			
				 mysql_query("INSERT INTO TRNENTRADA_INVENTARIO (IdEncabezadoEnInventario, CANTIDAD,IDMATPRIMA, IDUNIDAD ) VALUES ('".$enca."','".$fill['CANTIDADMATPRIMA']."','".$fill['MATERIAPRIMA']."','".$fill['IDUNIDAD']."')") or die(mysql_error());
			mysql_query("Update CATMATERIAPRIMA set CantDisponible=$nueva where IDMATPRIMA= $mat");
     } 
}?> 
</head> 
 
<body> 
</body> 
</html>