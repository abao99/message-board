<?php require_once('Connections/gbConn.php'); ?>
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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO post (P_Name, P_Icon, P_Mail, P_URL, P_MSN, P_Content, P_IP, P_Private, P_Date) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['P_Name'], "text"),
                       GetSQLValueString($_POST['P_Icon'], "text"),
                       GetSQLValueString($_POST['P_Mail'], "text"),
                       GetSQLValueString($_POST['P_URL'], "text"),
                       GetSQLValueString($_POST['P_MSN'], "text"),
                       GetSQLValueString($_POST['P_Content'], "text"),
                       GetSQLValueString($_POST['P_IP'], "text"),
                       GetSQLValueString($_POST['P_Private'], "int"),
                       GetSQLValueString($_POST['P_Date'], "date"));

  mysql_select_db($database_gbConn, $gbConn);
  $Result1 = mysql_query($insertSQL, $gbConn) or die(mysql_error());

  $insertGoTo = "index.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>無標題文件</title>
<script src="tinymce/tinymce.min.js"></script>
<script>tinymce.init({ selector:'textarea' });</script>


<style type="text/css">
.aaa {
	color: #F90;
}
</style></head>

<body>
<form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="700" border="0" align="center">
    <tr>
      <td bgcolor="#0066CC" class="aaa">新增留言</td>
      <td bgcolor="#0066CC">&nbsp;</td>
    </tr>
    <tr>
      <td bgcolor="#CCCCCC">暱稱:
        <label for="P_Name"></label>
      <input name="P_Name" type="text" id="P_Name" value="訪客" /></td>
      <td bgcolor="#CCCCCC">信箱:
      <input type="text" name="P_Mail" id="P_Mail" /></td>
    </tr>
    <tr>
      <td bgcolor="#CCCCCC">網址:
      <input type="text" name="P_URL" id="P_URL" /></td>
      <td bgcolor="#CCCCCC">MSN:
      <input type="text" name="P_MSN" id="P_MSN" /></td>
    </tr>
    <tr>
      <td bgcolor="#CCCCCC">圖示:
        <label for="P_Icon"></label>
        <select name="P_Icon" id="P_Icon">
          <option value="icon/m1.png">男生-笑臉</option>
          <option value="icon/m2.png">男生-尷尬</option>
          <option value="icon/f1.png">女生-笑臉</option>
          <option value="icon/f2.png">女生-沮喪</option>
        </select>
      <input name="P_IP" type="hidden" id="P_IP" value="<?php echo $_SERVER['REMOTE_ADDR']; ?>" />
      <input name="P_Date" type="hidden" id="P_Date" value="<?php echo date('Y-m-d H:i:s'); ?>" /></td>
      <td bgcolor="#CCCCCC">悄悄話:
        <input type="radio" name="P_Private" id="radio" value="1" />
是
<label for="P_Private">
  <input name="P_Private" type="radio" id="radio2" value="0" checked="checked" />
  否 </label></td>
    </tr>
    <tr>
      <td bgcolor="#CCCCCC">&nbsp;</td>
      <td bgcolor="#CCCCCC">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2"><label for="P_Content"></label>
      <textarea name="P_Content" id="P_Content" cols="45" rows="5"></textarea></td>
    </tr>
    <tr>
      <td bgcolor="#CCCCCC">&nbsp;</td>
      <td align="right" bgcolor="#CCCCCC"><input type="submit" name="button" id="button" value="送出" /></td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form1" />
</form>
</body>
</html>