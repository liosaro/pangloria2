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
require_once('../../../Connections/basepangloria.php');

 $q_prove = "SELECT * FROM CATPROVEEDOR where ELIMIN = 0";
   mysql_select_db($database_basepangloria, $basepangloria);
   $res4 = mysql_query($q_prove, $basepangloria) or die(mysql_error());
   //$row2 = mysql_fetch_assoc($res2);
// consulta para tomar el correo del proveedor
   $cmb_prove = '<select id="proveedor"  name="proveedor">';
   while ($fila = mysql_fetch_assoc($res4)) {
        $cmb_prove .= '<option value="'.$fila['CORREOPROVEEDOR'].'">'.$fila['NOMBREPROVEEDOR'].'</option>';
   }
   $cmb_prove .= '</select>';



 ?>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />

<title>Formulario X</title>
</head>


<body>

        <div id="formulario">
       <?php
  $fecha_hoy = date('d/m/y');

 $usu = '';

 $usu  = strtoupper($_SESSION['login_usuario']);   // me dice quien esta conectado!




$numsol = $nummax + 1 ;

$datos = '<form id="sole" name="sole" enctype="multipart/form-data" action="enviar.php" method="post">
     <table width="753" border="0" align="center">
      <tr>
        <th width="364" scope="col"><div align="center"><img src="../../../imagenes/logotipo.png" width="250" height="125"></div></th>
        <th width="803" scope="col"><div align="center">
          <p><font color="#006699" size="6" face="Calibri"> SOLICITUD DE COTIZACION PAN GLORIA</font></p>
          </div></th>
      </tr>
      <tr>
        <th colspan="2" scope="col"><div align="left"><font color="#006699" size="+4">_________________________________</font></div></th>
      </tr>
    </table>
    <div align="left"></div>
    <table border = "0" width="753" align="center">
      <tr>
      <td colspan="1" align="left" valign="top" height="65px" ><font face="calibri" color="#808080" >No. Solicitud: &nbsp; </font><big><font font face="Calibri" color="#808080"> '.$numsol.' </font></big><br></td>
        <input type="hidden" name="numsol" id="numsol" face="calibri" color="#808080" value="'.$numsol.'">
      <td colspan="1" align="right" valign="top" height="65px" ><font face="calibri" color="#808080" >Fecha: </font><big><font font face="Calibri" color="#808080"> '.$fecha_hoy.' </fecha></big><br></td>
      </tr>
      <tr>
        <td width="315"><font face="calibri" color="#808080" >Enviado a:</font>:</td>
        <td>'.$cmb_prove.'</td>
      </tr>
      <tr>
        <td><font face="calibri" color="#808080">Solicitante</font>:</td>
        <td><input type="text" name="solicitante" size="50" face="calibri" value="'.$usu.'"  /></td>
      </tr>
      <tr>
        <td><font face="calibri" color="#808080">Material Requerido</font>:</td>
        <td><font face="calibri"><input type="text" name="requerimiento" size="50"/></font></td>
      </tr>
      <tr>
        <td><font face="calibri" color="#808080">Descripcion</font>:</td>
        <td><font face="calibri"><textarea rows="4" name = "descripcion" cols="37" ></textarea></font></td>
      </tr>
      <tr>
        <td><font face="calibri" color="#808080">Detalle Solicitado</font>:</td>
        <td><font face="calibri"><textarea rows="4" name = "detalles" cols="37" ></textarea></font></td>
      </tr>
      <tr>
        <td><font face="calibri" color="#808080">Usuarios/Departamentos relacionados</font>:</td>
        <td><input type="text" name="usuarios" size="50" face="calibri" /></td>
      </tr>
      <tr>
        <td><font face="calibri" color="#808080">Comentarios</font>:</td>
        <td><textarea rows="4" name = "comentarios" cols="37" ></textarea></td>
      </tr>
       <tr>
        <td><font face="calibri" color="#808080">Definir Prioridad</font>:</td>
        <td><select name="prioridad" value="options"
          <option selected="selected" >Seleccione...</option>
          <option >Urgente</option>
          <option >Normal</option>
          <option >Bajo</option>
        </select>        </td>
      </tr>
     <tr>
        <td><font face="calibri" color="#808080">Archivo de Referencia</font>:</td>
        <td><img src="../imagenes/attach.png" width="20" height="20" />
        <input type="file" name="fileToUpload"  id="fileToUpload" size="28">

      </td>
           </tr>
             </table>
     <div align="center"><p><font color="#999999" face="Calibri" >Enviar Solicitud</font><br><input type="image" name="enviar" src="../imagenes/sendemail.png"></p>
     </div>

  </form>';

  echo ($datos);


          ?>
        </div>

</body>
</html>
