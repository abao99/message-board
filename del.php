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

if ((isset($_GET['Topic_ID'])) && ($_GET['Topic_ID'] != "")) {
  $deleteSQL = sprintf("DELETE FROM post WHERE Topic_ID=%s",
                       GetSQLValueString($_GET['Topic_ID'], "int"));

  mysql_select_db($database_gbConn, $gbConn);
  $Result1 = mysql_query($deleteSQL, $gbConn) or die(mysql_error());
}

if ((isset($_GET['Topic_ID'])) && ($_GET['Topic_ID'] != "")) {
  $deleteSQL = sprintf("DELETE FROM reply WHERE R_Topic=%s",
                       GetSQLValueString($_GET['Topic_ID'], "int"));

  mysql_select_db($database_gbConn, $gbConn);
  $Result1 = mysql_query($deleteSQL, $gbConn) or die(mysql_error());

  $deleteGoTo = "index.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>無標題文件</title>
</head>

<body>
</body>
</html>