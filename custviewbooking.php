<?php require_once('Connections/localhost.php'); ?>
<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['PrevUrl']);
	
  $logoutGoTo = "index.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

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
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "custlogin2.php";
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
<?php @session_start(); ?>
<?php require_once('Connections/localhost.php'); ?>
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

if ((isset($_POST['remove'])) && ($_POST['remove'] != "")) {
  $deleteSQL = sprintf("DELETE FROM breakfast WHERE Breakfast_ID=%s",
                       GetSQLValueString($_POST['remove'], "int"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($deleteSQL, $localhost) or die(mysql_error());

  $deleteGoTo = "custremovebooking.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
}

if ((isset($_POST['remove1'])) && ($_POST['remove1'] != "")) {
  $deleteSQL = sprintf("DELETE FROM lunch WHERE Lucnch_ID=%s",
                       GetSQLValueString($_POST['remove1'], "int"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($deleteSQL, $localhost) or die(mysql_error());

  $deleteGoTo = "custremovebooking.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
}

if ((isset($_POST['remove2'])) && ($_POST['remove2'] != "")) {
  $deleteSQL = sprintf("DELETE FROM dinner WHERE Dinner_ID=%s",
                       GetSQLValueString($_POST['remove2'], "int"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($deleteSQL, $localhost) or die(mysql_error());

  $deleteGoTo = "custremovebooking.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
}

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

$colname_Index = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_Index = $_SESSION['MM_Username'];
}
mysql_select_db($database_localhost, $localhost);
$query_Index = sprintf("SELECT * FROM customer WHERE Username = %s", GetSQLValueString($colname_Index, "text"));
$Index = mysql_query($query_Index, $localhost) or die(mysql_error());
$row_Index = mysql_fetch_assoc($Index);
$totalRows_Index = mysql_num_rows($Index);

$maxRows_Breakfast = 10;
$pageNum_Breakfast = 0;
if (isset($_GET['pageNum_Breakfast'])) {
  $pageNum_Breakfast = $_GET['pageNum_Breakfast'];
}
$startRow_Breakfast = $pageNum_Breakfast * $maxRows_Breakfast;

$colname_Breakfast = "-1";
if (isset($_GET['Customer_ID'])) {
  $colname_Breakfast = $_GET['Customer_ID'];
}
mysql_select_db($database_localhost, $localhost);
$query_Breakfast = sprintf("SELECT * FROM breakfast WHERE Customer_ID = %s", GetSQLValueString($colname_Breakfast, "int"));
$query_limit_Breakfast = sprintf("%s LIMIT %d, %d", $query_Breakfast, $startRow_Breakfast, $maxRows_Breakfast);
$Breakfast = mysql_query($query_limit_Breakfast, $localhost) or die(mysql_error());
$row_Breakfast = mysql_fetch_assoc($Breakfast);

if (isset($_GET['totalRows_Breakfast'])) {
  $totalRows_Breakfast = $_GET['totalRows_Breakfast'];
} else {
  $all_Breakfast = mysql_query($query_Breakfast);
  $totalRows_Breakfast = mysql_num_rows($all_Breakfast);
}
$totalPages_Breakfast = ceil($totalRows_Breakfast/$maxRows_Breakfast)-1;

$colname_Lunch = "-1";
if (isset($_GET['Customer_ID'])) {
  $colname_Lunch = $_GET['Customer_ID'];
}
mysql_select_db($database_localhost, $localhost);
$query_Lunch = sprintf("SELECT * FROM lunch WHERE Customer_ID = %s", GetSQLValueString($colname_Lunch, "int"));
$Lunch = mysql_query($query_Lunch, $localhost) or die(mysql_error());
$row_Lunch = mysql_fetch_assoc($Lunch);
$totalRows_Lunch = mysql_num_rows($Lunch);

$colname_Dinner = "-1";
if (isset($_GET['Customer_ID'])) {
  $colname_Dinner = $_GET['Customer_ID'];
}
mysql_select_db($database_localhost, $localhost);
$query_Dinner = sprintf("SELECT * FROM dinner WHERE Customer_ID = %s", GetSQLValueString($colname_Dinner, "int"));
$Dinner = mysql_query($query_Dinner, $localhost) or die(mysql_error());
$row_Dinner = mysql_fetch_assoc($Dinner);
$totalRows_Dinner = mysql_num_rows($Dinner);
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Savuer De France</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/modern-business.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="css/sb-admin.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="css/plugins/morris.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

       <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php"><strong>Saveurs De France</strong></a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <a href="about.html">About</a>
                    </li>
                     <li>
                        <a href=" ">News and Informaton</a>
                    </li>
                    <li>
                        <a href="contacts.php">Contact</a>
                    </li>
                    <li >
                                <a href="portfolio-3-col.html">Menu</a>
                    </li>
                   
                    <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?php echo $row_Index['Cust_firstname']; ?> <?php echo $row_Index['Cust_lastname']; ?><b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="#"><i class="fa fa-fw fa-user"></i> Profile</a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-fw fa-envelope"></i> Inbox</a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-fw fa-gear"></i> Settings</a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="<?php echo $logoutAction ?>"><i class="fa fa-fw fa-power-off"></i> Log Out</a>
                        </li>
                        
                    </ul>
                </li>
                    
                </ul>
                 
            </div>
            
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>


    <!-- Page Content -->
    <div class="container" style="background-color:white;">

        <!-- Page Heading/Breadcrumbs -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Welcome, 
                    <?php echo $row_Index['Username']; ?>
                </h1>
                <ol class="breadcrumb">
                    
                    
        </div>
        <!-- /.row -->
                </ol>
            </div>

        <!-- Content Row -->
        <div class="row">
            <!-- Sidebar Column -->
            <div class="col-md-3" >
                <div class="list-group" >
                	
 

                                    <a href="#" class="list-group-item " style="background-color:rgb(139,69,19); color:white; ">Manage Booking</a>
                    <a href="custupdateinfo.php" class="list-group-item" style="background-color:rgb(139,69,19); color:white">Update Information</a>
                    <a href="#" class="list-group-item" style="background-color:rgb(139,69,19); color:white">Log Out</a>
                    
              </div>
            </div>
            <!-- Content Column -->
            <div class="col-md-9">
               <div class="row">
            <div class="col-md-8">
                <h3></h3>
         <p>
           <?php if ($totalRows_Breakfast == 0) { // Show if recordset empty ?>
            <strong>No Breakfast booking made </strong>
  <?php } // Show if recordset empty ?>

<?php if ($totalRows_Lunch == 0) { // Show if recordset empty ?>
  <strong>No Lunch booking made</strong>
  <?php } // Show if recordset empty ?>

<?php if ($totalRows_Dinner == 0) { // Show if recordset empty ?>
 <strong>No Dinner booking made </strong>
  <?php } // Show if recordset empty ?>
 </p>       
            <?php if ($totalRows_Breakfast > 0) { // Show if recordset not empty ?>     
         <table class="table"> 
       
      <thead> 
        <tr> <th>Breakfast ID</th>  <th>Party Size</th> <th>Date</th> <th>Bill</th> <th>Comment</th></tr> </thead> 
        <tbody>
                   
            <div class="row">
           
  <?php do { ?>
    <form name="form1" method="post"> 
      
      <tr class="success">
        <td><?php echo $row_Breakfast['Breakfast_ID']; ?></td> 
        
        <td><?php echo $row_Breakfast['Party_size']; ?></td> 
        <td><?php echo $row_Breakfast['Date']; ?></td> 
        <td><?php echo $row_Breakfast['Bill']; ?></td>
        <td><?php echo $row_Breakfast['Comment']; ?></td>
        <input name="remove" type="hidden" value="<?php echo $row_Breakfast['Breakfast_ID']; ?>">
          
      </tr>
      
    </form> 
    <?php } while ($row_Breakfast = mysql_fetch_assoc($Breakfast)); ?>
              
</tbody> 
       </table><?php } // Show if recordset not empty ?>
                            
            <?php if ($totalRows_Lunch > 0) { // Show if recordset not empty ?>            
  <table class="table"> 
       
      <thead> 
        <tr> <th>Lunch ID</th> <th>Party Size</th> <th>Date</th> <th>Bill</th> <th>Comment</th></tr> </thead> 
        <tbody>
                   
            <div class="row">
           
  <?php do { ?>
    <form name="form1" method="post"> 
      
      <tr class="success">
        <td><?php echo $row_Lunch['Lucnch_ID']; ?></td> 
        
        <td><?php echo $row_Lunch['Party_size']; ?></td> 
        <td><?php echo $row_Lunch['Date']; ?></td> 
        <td><?php echo $row_Lunch['Bill']; ?></td>
        <td><?php echo $row_Lunch['Comment']; ?></td>
        <input name="remove1" type="hidden" value="<?php echo $row_Lunch['Lucnch_ID']; ?>">
         
      </tr>
      
    </form> 
    <?php } while ($row_Lunch = mysql_fetch_assoc($Lunch)); ?>
              
</tbody> 
       </table><?php } // Show if recordset not empty ?>
        <?php if ($totalRows_Dinner > 0) { // Show if recordset not empty ?>
       <table class="table"> 
       
      <thead> 
        <tr> <th>Dinner ID</th>> <th>Party Size</th> <th>Date</th> <th>Bill</th> <th>Comment</th></tr> </thead> 
        <tbody>
                   
            <div class="row">
           
  <?php do { ?>
    <form name="form1" method="post"> 
      
      <tr class="success">
        <td><?php echo $row_Dinner['Dinner_ID']; ?></td> 
       
        <td><?php echo $row_Dinner['Party_size']; ?></td> 
        <td><?php echo $row_Dinner['Date']; ?></td> 
        <td><?php echo $row_Dinner['Bill']; ?></td>
        <td><?php echo $row_Dinner['Comment']; ?></td>
        <input name="remove2" type="hidden" value="<?php echo $row_Dinner['Dinner_ID']; ?>">
           
      </tr>
      
    </form> 
    <?php } while ($row_Dinner = mysql_fetch_assoc($Dinner)); ?>
            
</tbody> 
       </table>  <?php } // Show if recordset not empty ?>
                
            </div>

        </div>
        <!-- /.row -->
</div>
            
        </div>
        <!-- /.row -->

        <hr>
        

        <!-- Footer -->
        <footer>
            <div class="row">
                <div class="col-lg-12">
                    <p>Copyright &copy; Your Website 2014</p>
                </div>
            </div>
        </footer>

    </div>
    <!-- /.container -->

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

</body>

</html>
<?php


mysql_free_result($Lunch);

mysql_free_result($Dinner);

mysql_free_result($Index);

mysql_free_result($Breakfast);
?>
