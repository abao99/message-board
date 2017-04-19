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
  $insertSQL = sprintf("INSERT INTO reply (R_Topic, R_Name, R_Icon, R_Mail, R_URL, R_MSN, R_Content, R_IP, R_Date) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['R_Topic'], "int"),
                       GetSQLValueString($_POST['R_Name'], "text"),
                       GetSQLValueString($_POST['R_Icon'], "text"),
                       GetSQLValueString($_POST['R_Mail'], "text"),
                       GetSQLValueString($_POST['R_URL'], "text"),
                       GetSQLValueString($_POST['R_MSN'], "text"),
                       GetSQLValueString($_POST['R_Content'], "text"),
                       GetSQLValueString($_POST['R_IP'], "text"),
                       GetSQLValueString($_POST['R_Date'], "date"));

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
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>無標題文件</title>
<style type="text/css">
.aaa {	color: #F90;
}
</style>

<script src="tinymce/tinymce.min.js"></script>
<script>tinymce.init({ selector:'textarea' });</script>
</head>

<body>
<form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="700" border="0" align="center">
    <tr>
      <td bgcolor="#0066CC" class="aaa">回覆留言</td>
      <td bgcolor="#0066CC">&nbsp;</td>
    </tr>
    <tr>
      <td bgcolor="#CCCCCC">暱稱:
        <label for="R_Name"></label>
        <input name="R_Name" type="text" id="R_Name" value="訪客" /></td>
      <td bgcolor="#CCCCCC">信箱:
        <input type="text" name="R_Mail" id="R_Mail" /></td>
    </tr>
    <tr>
      <td bgcolor="#CCCCCC">網址:
        <input type="text" name="R_URL" id="R_URL" /></td>
      <td bgcolor="#CCCCCC">MSN:
        <input type="text" name="R_MSN" id="R_MSN" /></td>
    </tr>
    <tr>
      <td bgcolor="#CCCCCC">圖示:
        <label for="R_Icon"></label>
        <select name="R_Icon" id="R_Icon">
          <option value="icon/m1.png">男生-笑臉</option>
          <option value="icon/m2.png">男生-尷尬</option>
          <option value="icon/f1.png">女生-笑臉</option>
          <option value="icon/f2.png">女生-沮喪</option>
        </select>
        <input name="R_IP" type="hidden" id="R_IP" value="<?php echo $_SERVER['REMOTE_ADDR']; ?>" />
        <input name="R_Date" type="hidden" id="R_Date" value="<?php echo date('Y-m-d H:i:s'); ?>" /></td>
      <td bgcolor="#CCCCCC"><input name="R_Topic" type="hidden" id="R_Topic" value="<?php echo $_GET['Topic_ID'] ?>"></td>
    </tr>
    <tr>
      <td bgcolor="#CCCCCC">&nbsp;</td>
      <td bgcolor="#CCCCCC">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2"><label for="R_Content"></label>
        <textarea name="R_Content" id="R_Content" cols="45" rows="5"></textarea></td>
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