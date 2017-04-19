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

$colname_rsreply = "-1";
if (isset($row_rsview['Topic_ID'])) {
  $colname_rsreply = $row_rsview['Topic_ID'];
}
mysql_select_db($database_gbConn, $gbConn);
$query_rsreply = sprintf("SELECT * FROM reply WHERE R_Topic = %s ORDER BY Reply_ID DESC", GetSQLValueString($colname_rsreply, "int"));
$rsreply = mysql_query($query_rsreply, $gbConn) or die(mysql_error());
$row_rsreply = mysql_fetch_assoc($rsreply);
$totalRows_rsreply = mysql_num_rows($rsreply);
?>

<style type="text/css">
.aa {	color: #F90;
}
</style>

<?php do { ?>

<table width="700" border="1" align="center">
  <tr>
    <td height="40" align="center" valign="middle" bgcolor="#0066FF" class="aa">回應人</td>
    <td height="40" colspan="2" align="center" valign="middle" bgcolor="#0066FF" class="aa">內容</td>
  </tr>
  <tr>
    <td rowspan="3" align="center" valign="middle"><p><img src="<?php echo $row_rsreply['R_Icon']; ?>"></p>
      <p><?php echo $row_rsreply['R_Name']; ?></p></td>
    <td height="35">&nbsp;</td>
    <td height="35" align="right" valign="middle"><img src="img/icon_ip.gif" width="15" height="18" title="<?php echo $row_rsreply['R_IP']; ?>">
    <?php if(isset($_SESSION['Authdone'])){?>
    	<img src="img/icon_delete.gif" width="15" height="18">
    <?php } ?>
    </td>
  </tr>
  <tr>
    <td colspan="2"><?php echo $row_rsreply['R_Content']; ?></td>
  </tr>
  <tr>
    <td height="35"><?php if(isset($row_rsreply['R_URL'])){ ?>
      <a href="<?php echo $row_rsreply['R_URL']; ?>"><img src="img/icon_www.gif" width="30" height="18" title="<?php echo $row_rsreply['R_URL']; ?>"></a>
      <?php }; ?>
      <?php if(isset($row_rsreply['R_Mail'])){ ?>
      <a href="mailto:<?php echo $row_rsreply['R_Mail']; ?>"><img src="img/icon_email.gif" width="30" height="18" title="<?php echo $row_rsreply['R_Mail']; ?>"></a>
      <?php }; ?>
      <?php if(isset($row_rsreply['R_MSN'])){ ?>
      <img src="img/icon_msn.gif" width="30" height="18" title="<?php echo $row_rsreply['R_MSN']; ?>">
      <?php }; ?></td>
    <td height="35">發表時間:<?php echo $row_rsreply['R_Date']; ?></td>
  </tr>
</table>
<?php } while ($row_rsreply = mysql_fetch_assoc($rsreply)); ?>
<?php
mysql_free_result($rsreply);
?>
