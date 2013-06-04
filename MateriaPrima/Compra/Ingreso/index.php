<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<script type="text/javascript">
function OnSubmitForm()
{
  if(document.form1.radio[0].checked == true)
  {
    document.form1.action ="orden/concotiza.php";
  }
  if(document.form1.radio[1].checked == true)
  {
    document.form1.action ="noorden/concotiza.php";
  }
  return true;
}
</script>
</head>

<body>
<p>Elija si la compra a ingresar Posee Orden de Compra:</p>
<form id="form1" name="form1" method="post" onsubmit="return OnSubmitForm();" action="">
  <p>
    <label>
      <input type="radio" name="radio" value="si" id="radio_0" />
      Si</label>
    <br />
    <label>
      <input type="radio" name="radio" value="No" id="radio_1" />
      No</label>
  </p>
  <p>
    <input type="submit" name="button" id="button" value="Siguiente" />
    <br />
  </p>
</form>
<p>&nbsp;</p>
</body>
</html>