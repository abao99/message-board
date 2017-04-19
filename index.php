<?php require_once('Connections/gbConn.php'); ?>
<?php
session_start();
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

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_rsview = 3;
$pageNum_rsview = 0;
if (isset($_GET['pageNum_rsview'])) {
  $pageNum_rsview = $_GET['pageNum_rsview'];
}
$startRow_rsview = $pageNum_rsview * $maxRows_rsview;

mysql_select_db($database_gbConn, $gbConn);
$query_rsview = "SELECT * FROM post ORDER BY Topic_ID DESC";
$query_limit_rsview = sprintf("%s LIMIT %d, %d", $query_rsview, $startRow_rsview, $maxRows_rsview);
$rsview = mysql_query($query_limit_rsview, $gbConn) or die(mysql_error());
$row_rsview = mysql_fetch_assoc($rsview);

if (isset($_GET['totalRows_rsview'])) {
  $totalRows_rsview = $_GET['totalRows_rsview'];
} else {
  $all_rsview = mysql_query($query_rsview);
  $totalRows_rsview = mysql_num_rows($all_rsview);
}
$totalPages_rsview = ceil($totalRows_rsview/$maxRows_rsview)-1;

$queryString_rsview = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsview") == false && 
        stristr($param, "totalRows_rsview") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsview = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsview = sprintf("&totalRows_rsview=%d%s", $totalRows_rsview, $queryString_rsview);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>無標題文件</title>
<style type="text/css">
.aa {
	color: #F90;
}
</style></head>

<body>
<table width="700" border="1" align="center">
  <tr>
    <td colspan="2" align="center" valign="middle">留言板</td>
  </tr>
  <tr>
    <td><a href="add.php" target="_top"><img src="img/add.png" width="61" height="24"></a></td>
    <td align="right" valign="middle"><a href="login.php"><img src="img/cp.png" width="61" height="23"></a></td>
  </tr>
  <tr>
    <td colspan="2" align="center" valign="middle" class="aa"> 留言 <?php echo ($startRow_rsview + 1) ?> 到 <?php echo min($startRow_rsview + $maxRows_rsview, $totalRows_rsview) ?> 筆 共 <?php echo $totalRows_rsview ?>筆</td>
  </tr>
</table>
<p>&nbsp;</p>
<?php do { ?>
  <table width="700" border="1" align="center">
    <tr>
      <td height="40" align="center" valign="middle" bgcolor="#0066FF" class="aa">發表人</td>
      <td height="40" colspan="2" align="center" valign="middle" bgcolor="#0066FF" class="aa">內容</td>
    </tr>
    <tr>
      <td rowspan="3" align="center" valign="middle"><p><img src="<?php echo $row_rsview['P_Icon']; ?>"></p>
      <p><?php echo $row_rsview['P_Name']; ?></p></td>
      <td height="35">&nbsp;</td>
      <td height="35" align="right" valign="middle"><a href="reply.php?Topic_ID=<?php echo $row_rsview['Topic_ID']; ?>"><img src="img/icon_quote.gif" width="30" height="18"></a><img src="img/icon_ip.gif" width="15" height="18" title="<?php echo $row_rsview['P_IP']; ?>">
    <?php if(isset($_SESSION['Authdone'])){?>
      <a href="del.php?Topic_ID=<?php echo $row_rsview['Topic_ID']; ?>"><img src="img/icon_delete.gif" width="15" height="18"></a>
      <?php } ?>
      </td>
    </tr>
    <tr>
      <td colspan="2"><?php echo $row_rsview['P_Content']; ?></td>
    </tr>
    <tr>
      <td height="35">
      <?php if(isset($row_rsview['P_URL'])){ ?>
      <a href="<?php echo $row_rsview['P_URL']; ?>"><img src="img/icon_www.gif" width="30" height="18"></a>
      <?php }; ?> 
	 
	 <?php if(isset($row_rsview['P_Mail'])){ ?>	
      <a href="mailto:<?php echo $row_rsview['P_Mail']; ?>"><img src="img/icon_email.gif" width="30" height="18"></a>
      <?php }; ?> 
	
	<?php if(isset($row_rsview['P_MSN'])){ ?>
      <img src="img/icon_msn.gif" width="30" height="18" title="<?php echo $row_rsview['P_MSN']; ?>">
      <?php }; ?> 

      </td>
      <td height="35">發表時間:<?php echo $row_rsview['P_Date']; ?></td>
    </tr>
    <tr>
      <td height="35" colspan="3" align="center" valign="middle"><?php include("rview.php"); ?>
</td>
    </tr>
  </table>
  <?php } while ($row_rsview = mysql_fetch_assoc($rsview)); ?>
<p>&nbsp;</p>
<table width="700" border="1" align="center">
  <tr>
    <td align="right" valign="middle">跳頁選單
      <table border="0">
        <tr>
          <td><?php if ($pageNum_rsview > 0) { // Show if not first page ?>
            <a href="<?php printf("%s?pageNum_rsview=%d%s", $currentPage, 0, $queryString_rsview); ?>"><img src="First.gif"></a>
            <?php } // Show if not first page ?></td>
          <td><?php if ($pageNum_rsview > 0) { // Show if not first page ?>
            <a href="<?php printf("%s?pageNum_rsview=%d%s", $currentPage, max(0, $pageNum_rsview - 1), $queryString_rsview); ?>"><img src="Previous.gif"></a>
            <?php } // Show if not first page ?></td>
          <td><?php if ($pageNum_rsview < $totalPages_rsview) { // Show if not last page ?>
            <a href="<?php printf("%s?pageNum_rsview=%d%s", $currentPage, min($totalPages_rsview, $pageNum_rsview + 1), $queryString_rsview); ?>"><img src="Next.gif"></a>
            <?php } // Show if not last page ?></td>
          <td><?php if ($pageNum_rsview < $totalPages_rsview) { // Show if not last page ?>
            <a href="<?php printf("%s?pageNum_rsview=%d%s", $currentPage, $totalPages_rsview, $queryString_rsview); ?>"><img src="Last.gif"></a>
            <?php } // Show if not last page ?></td>
        </tr>
      </table>
   </td>
  </tr>
</table>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rsview);
?>
