<?php require_once('../../../Connections/basepangloria.php'); ?>
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

$MM_restrictGoTo = "../../../seguridad.php";
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

mysql_select_db($database_basepangloria, $basepangloria);
$query_cotizacion = "SELECT IDENCABEZADO, IDPROVEEDOR, FECHACOTIZACION, PLAZOENTREGA FROM TRNCABEZACOTIZACION WHERE ELIMIN = 0";
$cotizacion = mysql_query($query_cotizacion, $basepangloria) or die(mysql_error());
$row_cotizacion = mysql_fetch_assoc($cotizacion);
$totalRows_cotizacion = mysql_num_rows($cotizacion);

?>
<!--

To change this template, choose Tools | Templates

and open the template in the editor.

-->



<?php

   require_once('../../../Connections/basepangloria.php');



   $q1 = "SELECT CEN.IDENCABEZADO,

                 CEN.IDVENDEDOR,

                 (SELECT NOM from CATVENDEDOR_PROV where CATVENDEDOR_PROV.IDVENDEDOR = CEN.IDVENDEDOR) NOMBRE_VENDEDOR,

                 CEN.IDPROVEEDOR,

                 (SELECT NOMBREPROVEEDOR from CATPROVEEDOR where CATPROVEEDOR.IDPROVEEDOR = CEN.IDPROVEEDOR) NOMBRE_PROVEEDOR,

                 CEN.IDEMPLEADO,

                 (SELECT NOMBREEMPLEADO from CATEMPLEADO where CATEMPLEADO.IDEMPLEADO = CEN.IDEMPLEADO) NOMBRE_EMPLEADO,

                 (select TIPO from CATCONDICIONPAGO WHERE CATCONDICIONPAGO.IDCONDICION = CEN.IDCONDICION) TIPO_PAGO,



                 CEN.FECHACOTIZACION,

                 CEN.VALIDEZOFERTA,

                 CEN.PLAZOENTREGA,

                 CDE.IDDETALLE,

                 CDE.IDMATPRIMA,

                 (SELECT DESCRIPCION from CATMATERIAPRIMA where CATMATERIAPRIMA.IDMATPRIMA = CDE.IDMATPRIMA)MATERIA_PRIMA,

                 CDE.IDENCABEZADO IDENCABEZADO_DET,

                 CDE.IDUNIDAD,

                 CDE.CANTPRODUCTO,

                 CDE.PRECIOUNITARIO

          FROM TRNCABEZACOTIZACION CEN, TRNDETALLECOTIZACION CDE

          WHERE CEN.IDENCABEZADO = CDE.IDENCABEZADO

            AND CEN.ELIMIN       = 0

            AND CDE.ELIMINA      = 0

          ORDER BY CEN.IDENCABEZADO,

                   CDE.IDDETALLE";

   

    mysql_select_db($database_basepangloria, $basepangloria);

    $res1 = mysql_query($q1, $basepangloria) or die(mysql_error());

    

   

?>



<!DOCTYPE html>

<html>

    <head>

        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">

        <title>Ingreso de Cotizaci&oacute;n</title>



        <script language="javascript" type="text/javascript">

                  function addLinha(){



                        var tab      = document.getElementById("detalle");

                        var pkcot    = document.getElementById("id_cotizacion").value;



                        var linha        = tab.insertRow(tab.rows.length);

                        var lin          = (tab.rows.length - 2);





                        coluna1 = linha.insertCell(0);

                        coluna1.align = "center";

                        coluna1.innerHTML = '<tr><td><input type="text" name="cot[]"  value="'+ pkcot +'"></td>';



                        coluna1 = linha.insertCell(1);

                        coluna1.align = "center";

                        coluna1.innerHTML = '<td><?php echo $cmb_matpri ?></td>';



                        coluna1 = linha.insertCell(2);

                        coluna1.align = "center";

                        coluna1.innerHTML = '<td><input type="text" name="um[]"></td>';



                        coluna1 = linha.insertCell(3);

                        coluna1.align = "center";

                        coluna1.innerHTML = '<td><input type="text" name="qtde[]"></td>';



                        coluna1 = linha.insertCell(4);

                        coluna1.align = "center";

                        coluna1.innerHTML = '<td><input type="text" name="pu[]"></td>';



                        coluna1 = linha.insertCell(5);

                        coluna1.align = "center";

                        coluna1.innerHTML = '<td><input type="button"  onClick="addLinha()"  value="+"></td>';





                 return true;

                }

        </script>



    <link href="../../../css/forms.css" rel="stylesheet" type="text/css">
    </head>

    <body>

    <table width="820" style="font-family: verdana; font-size: 0.9em;">

        <td align="center" bgcolor="#999999"><form name="principal" id="principal" enctype="multipart/form-data" method="post"  action="mostrarConsulta.php">

          <h1>&nbsp;</h1>

           

        <h1 style="font-family: verdana; font-size: 0.9em; font-weight: bold; text-align: center;">
        <h1>B&uacute;squeda de Cotizaci&oacute;n</h1>

        </table>

        <table style="font-family: verdana; font-size: 0.9em;">

            <tr>

                <td>Id Cotizaci&oacute;n</td>

                <td><input type="text" id="id_cotizacion"  name="id_cotizacion" size="15" value=""> <input type="submit" value="BUSCAR"></td>

            </tr>

            <!--<tr>

                <td>Empleado</td>

                <td><input type="text" id="empleado"  name="empleado" size="15" value=""></td>

            </tr>

             <tr>

                <td>Proveedor</td>

                <td><input type="text" id="proveedor"  name="proveedor" size="15" value=""></td>

            </tr>



                <tr>

                <td>Vendedor</td>

                <td><input type="text" id="vendedor"  name="vendedor" size="15" value=""></td>

            </tr>-->



        </table>

       



        </br>
    <table border="1" cellpadding="0" cellspacing="0">
      <tr class="retabla">
            <td align="center" bgcolor="#000000">No. de Cotizaci&oacute;n</td>
            <td align="center" bgcolor="#000000">Proveedor</td>
            <td align="center" bgcolor="#000000">Fecha de Cotizaci&oacute;n</td>
            <td align="center" bgcolor="#000000">Plazo de Entrega</td>
      </tr>
          <?php do { ?>
         <?php  mysql_select_db($database_basepangloria, $basepangloria);
$IDPROVEEDOR=$row_cotizacion['IDPROVEEDOR'];
$query_Recordset1 = "SELECT NOMBREPROVEEDOR FROM CATPROVEEDOR WHERE IDPROVEEDOR = $IDPROVEEDOR";
$Recordset1 = mysql_query($query_Recordset1, $basepangloria) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1); ?>
            <tr>
              <td><?php echo $row_cotizacion['IDENCABEZADO']; ?></td>
              <td><?php echo $row_Recordset1['NOMBREPROVEEDOR']; ?></td>
              <td><?php echo $row_cotizacion['FECHACOTIZACION']; ?></td>
              <td><?php echo $row_cotizacion['PLAZOENTREGA']; ?></td>
            </tr>
            <?php } while ($row_cotizacion = mysql_fetch_assoc($cotizacion)); ?>
      </table>
    </body>

</html>
<?php
mysql_free_result($cotizacion);

mysql_free_result($Recordset1);
?>
