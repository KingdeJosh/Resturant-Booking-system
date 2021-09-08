<?php @session_start(); ?>
<?php require_once('Connections/localhost.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "2";
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

$MM_restrictGoTo = "Emplogin.php";
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

$currentPage = $_SERVER["PHP_SELF"];

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "update")) {
  $updateSQL = sprintf("UPDATE empolyee SET Emp_firstname=%s, Emp_lastname=%s, Contact_no=%s, Username=%s, Email=%s, Password=%s, Emp_role=%s, Address=%s WHERE Employee_ID=%s",
                       GetSQLValueString($_POST['Firstname'], "text"),
                       GetSQLValueString($_POST['Lastname'], "text"),
                       GetSQLValueString($_POST['Telephone'], "int"),
                       GetSQLValueString($_POST['Username'], "text"),
                       GetSQLValueString($_POST['Email'], "text"),
                       GetSQLValueString($_POST['Password'], "text"),
                       GetSQLValueString($_POST['Emprole'], "text"),
                       GetSQLValueString($_POST['Address'], "text"),
                       GetSQLValueString($_POST['update'], "int"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($updateSQL, $localhost) or die(mysql_error());

  $updateGoTo = "Updateemp.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$maxRows_Update = 10;
$pageNum_Update = 0;
if (isset($_GET['pageNum_Update'])) {
  $pageNum_Update = $_GET['pageNum_Update'];
}
$startRow_Update = $pageNum_Update * $maxRows_Update;

mysql_select_db($database_localhost, $localhost);
$query_Update = "SELECT * FROM empolyee ORDER BY Employee_ID DESC";
$query_limit_Update = sprintf("%s LIMIT %d, %d", $query_Update, $startRow_Update, $maxRows_Update);
$Update = mysql_query($query_limit_Update, $localhost) or die(mysql_error());
$row_Update = mysql_fetch_assoc($Update);

if (isset($_GET['totalRows_Update'])) {
  $totalRows_Update = $_GET['totalRows_Update'];
} else {
  $all_Update = mysql_query($query_Update);
  $totalRows_Update = mysql_num_rows($all_Update);
}
$totalPages_Update = ceil($totalRows_Update/$maxRows_Update)-1;

$colname_Index = "-1";
if (isset($_POST['MM_Username'])) {
  $colname_Index = $_POST['MM_Username'];
}
mysql_select_db($database_localhost, $localhost);
$query_Index = sprintf("SELECT * FROM empolyee WHERE Username = %s", GetSQLValueString($colname_Index, "text"));
$Index = mysql_query($query_Index, $localhost) or die(mysql_error());
$row_Index = mysql_fetch_assoc($Index);
$totalRows_Index = mysql_num_rows($Index);

$queryString_Update = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_Update") == false && 
        stristr($param, "totalRows_Update") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_Update = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_Update = sprintf("&totalRows_Update=%d%s", $totalRows_Update, $queryString_Update);
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
    <link href="css/sb-admin.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php"><strong>Savuer De France Admin</strong></a>
            </div>
            <!-- Top Menu Items -->
            <ul class="nav navbar-right top-nav">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-envelope"></i> <b class="caret"></b></a>
                    <ul class="dropdown-menu message-dropdown">
                        <li class="message-preview">
                            <a href="#">
                                <div class="media">
                                    <span class="pull-left">
                                        <img class="media-object" src="http://placehold.it/50x50" alt="">
                                    </span>
                                    <div class="media-body">
                                        <h5 class="media-heading">
                                            <strong>John Smith</strong>
                                        </h5>
                                        <p class="small text-muted"><i class="fa fa-clock-o"></i> Yesterday at 4:32 PM</p>
                                        <p>Lorem ipsum dolor sit amet, consectetur...</p>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="message-preview">
                            <a href="#">
                                <div class="media">
                                    <span class="pull-left">
                                        <img class="media-object" src="http://placehold.it/50x50" alt="">
                                    </span>
                                    <div class="media-body">
                                        <h5 class="media-heading">
                                            <strong>John Smith</strong>
                                        </h5>
                                        <p class="small text-muted"><i class="fa fa-clock-o"></i> Yesterday at 4:32 PM</p>
                                        <p>Lorem ipsum dolor sit amet, consectetur...</p>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="message-preview">
                            <a href="#">
                                <div class="media">
                                    <span class="pull-left">
                                        <img class="media-object" src="http://placehold.it/50x50" alt="">
                                    </span>
                                    <div class="media-body">
                                        <h5 class="media-heading">
                                            <strong>John Smith</strong>
                                        </h5>
                                        <p class="small text-muted"><i class="fa fa-clock-o"></i> Yesterday at 4:32 PM</p>
                                        <p>Lorem ipsum dolor sit amet, consectetur...</p>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="message-footer">
                            <a href="#">Read All New Messages</a>
                        </li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bell"></i> <b class="caret"></b></a>
                    <ul class="dropdown-menu alert-dropdown">
                        <li>
                            <a href="#">Alert Name <span class="label label-default">Alert Badge</span></a>
                        </li>
                        <li>
                            <a href="#">Alert Name <span class="label label-primary">Alert Badge</span></a>
                        </li>
                        <li>
                            <a href="#">Alert Name <span class="label label-success">Alert Badge</span></a>
                        </li>
                        <li>
                            <a href="#">Alert Name <span class="label label-info">Alert Badge</span></a>
                        </li>
                        <li>
                            <a href="#">Alert Name <span class="label label-warning">Alert Badge</span></a>
                        </li>
                        <li>
                            <a href="#">Alert Name <span class="label label-danger">Alert Badge</span></a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#">View All</a>
                        </li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?php echo $row_Index['Emp_firstname']; ?> <?php echo $row_Index['Emp_lastname']; ?><b class="caret"></b></a>
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
                            <a href="#"><i class="fa fa-fw fa-power-off"></i> Log Out</a>
                        </li>
                    </ul>
                </li>
            </ul>
             <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav side-nav">
                    <li >
                        <a href="Admins.php"><i class="fa fa-fw fa-dashboard"></i> Dashboard</a>
                    </li>
                    <li>
                       <li>
                        <a href="Viewcust.php"><i class="fa fa-fw fa-table"></i> View Customers</a>
                    </li>
                    <li>
                        <a href="ViewBookings.php"><i class="fa fa-fw fa-table"></i> View Customer Booking</a>
                    </li>
                    
                   
                    
                    
                    
                    <li class="active">
                        <a href="javascript:;" data-toggle="collapse" data-target="#demo"><i class="fa fa-fw fa-arrows-v"></i> Manage Employee Details <i class="fa fa-fw fa-caret-down"></i></a>
                        <ul id="demo" class="collapse">
                           <li>
                        <a href="Updateemp.php"><i class="fa fa-fw fa-edit"></i> Update Employee Details</a>
                    </li>
                    <li>
                        <a href="Removeemp.php"><i class="fa fa-fw fa-edit"></i> Remove Employee Details</a>
                    </li>
                    <li>
                        <a href="Addemp.php"><i class="fa fa-fw fa-edit"></i> Add Employee Details</a>
                    </li>
                        </ul>
                    </li>
                  
                   
                    <li >
                        <a href="Viewemp2.php"><i class="fa fa-fw fa-dashboard"></i> View Employee</a>
                    </li>
                     
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </nav>

        <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                           <p><h1><strong>Update Employee Records</strong></h1></p>  
                        </h1>
                        <ol class="breadcrumb">
                            <li>
                                <i class="fa fa-dashboard"></i>  <a href="Admins.php">Dashboard</a>
                            </li>
                            <li class="active">
                                <i class="fa fa-file"></i> Update Employee Records
                            </li>
                        </ol>
                    </div>
                </div>
                <!-- /.row -->
        <table class="table"> 
     <div class="row text-center"><div class="col-lg-12">
                <ul class="pagination">
                         
      Showing <?php echo ($startRow_Update + 1) ?> to <?php echo min($startRow_Update + $maxRows_Update, $totalRows_Update) ?> of <?php echo $totalRows_Update ?>
      </ul>
            </div></div>
      <thead> 
        <tr> 
          <th>ID</th> <th>Firstname</th> <th>Lastname</th><th>Employee Role</th><th>Email</th><th>Username</th><th>Password</th><th>Telephone</th> <th>Address</th> 
        </tr> 
      </thead> 
      <tbody> 
      
     
       
         
          <?php if ($totalRows_Update > 0) { // Show if recordset not empty ?>
             <?php do { ?>
             <form action="<?php echo $editFormAction; ?>"  method="POST" name="update">
               <tr class="success">
                 <td><?php echo $row_Update['Employee_ID']; ?></td>
                 <td><input type="text" name="Firstname" class="form-control" id="Firstname"  value="<?php echo $row_Update['Emp_firstname']; ?>"></td>
                 <td><input type="text" name="Lastname" class="form-control" id="Lastname"  value="<?php echo $row_Update['Emp_lastname']; ?>"></td>
                 <td><input type="text" name="Emprole" class="form-control" id="Emprole"  value="<?php echo $row_Update['Emp_role']; ?>"></td>
                 <td><input type="email" name="Email" class="form-control" id="Email"  value="<?php echo $row_Update['Email']; ?>"></td>
                 <td><input type="text" name="Username" class="form-control" id="Username"  value="<?php echo $row_Update['Username']; ?>"></td>
                 <td><input type="text" name="Password" class="form-control" id="Password"  value="<?php echo $row_Update['Password']; ?>"></td>
                 <td><input name="Telephone"type="text" class="form-control" id="Telephone"  value="<?php echo $row_Update['Contact_no']; ?>"></td>
                 <td><input type="text" name="Address" class="form-control" id="Address"  value="<?php echo $row_Update['Address']; ?>"></td>
                 <td><input name="update" type="hidden" value="<?php echo $row_Update['Employee_ID']; ?>">
                   <button type="submit" class="btn btn-success">Update</button></td>
             </tr>
               <input type="hidden" name="MM_update" value="update">
             </form>
               <?php } while ($row_Update = mysql_fetch_assoc($Update)); ?>
             <?php } // Show if recordset not empty ?></tbody> 
  </table>
  
       <!-- Pagination -->
        <div class="row text-center">
            <div class="col-lg-12">
                <ul class="pagination">
                  <?php if ($pageNum_Update > 0) { // Show if not first page ?>
                    <li> <a href="<?php printf("%s?pageNum_Update=%d%s", $currentPage, max(0, $pageNum_Update - 1), $queryString_Update); ?>">&laquo; Previous Page</a> </li>
                    <?php } // Show if not first page ?>
                  <?php if ($pageNum_Update < $totalPages_Update) { // Show if not last page ?>
  <li>
    <a href="<?php printf("%s?pageNum_Update=%d%s", $currentPage, min($totalPages_Update, $pageNum_Update + 1), $queryString_Update); ?>">Next Page &raquo;</a>
  </li>
  <?php } // Show if not last page ?>
                </ul>
            </div>
      </div>

     <!-- Pagination -->
        
        <!-- /.row --> 

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

</body>

</html>

<?php
mysql_free_result($Update);

mysql_free_result($Index);
?>