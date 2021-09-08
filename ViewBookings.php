<?php @session_start(); ?>
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

$colname_Index = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_Index = $_SESSION['MM_Username'];
}
mysql_select_db($database_localhost, $localhost);
$query_Index = sprintf("SELECT * FROM empolyee WHERE Username = %s", GetSQLValueString($colname_Index, "text"));
$Index = mysql_query($query_Index, $localhost) or die(mysql_error());
$row_Index = mysql_fetch_assoc($Index);
$totalRows_Index = "-1";
if (isset($_SESSION['MM_Username'])) {
  $totalRows_Index = $_SESSION['MM_Username'];
}
$colname_Index = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_Index =$_SESSION['MM_Username'] ;
}
mysql_select_db($database_localhost, $localhost);
$query_Index = sprintf("SELECT * FROM empolyee WHERE Username = %s", GetSQLValueString($colname_Index, "text"));
$Index = mysql_query($query_Index, $localhost) or die(mysql_error());
$row_Index = mysql_fetch_assoc($Index);
$totalRows_Index = mysql_num_rows($Index);

$maxRows_morning = 10;
$pageNum_morning = 0;
if (isset($_GET['pageNum_morning'])) {
  $pageNum_morning = $_GET['pageNum_morning'];
}
$startRow_morning = $pageNum_morning * $maxRows_morning;

mysql_select_db($database_localhost, $localhost);
$query_morning = "SELECT * FROM breakfast ORDER BY `Date` ASC";
$query_limit_morning = sprintf("%s LIMIT %d, %d", $query_morning, $startRow_morning, $maxRows_morning);
$morning = mysql_query($query_limit_morning, $localhost) or die(mysql_error());
$row_morning = mysql_fetch_assoc($morning);

if (isset($_GET['totalRows_morning'])) {
  $totalRows_morning = $_GET['totalRows_morning'];
} else {
  $all_morning = mysql_query($query_morning);
  $totalRows_morning = mysql_num_rows($all_morning);
}
$totalPages_morning = ceil($totalRows_morning/$maxRows_morning)-1;

$queryString_morning = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_morning") == false && 
        stristr($param, "totalRows_morning") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_morning = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_morning = sprintf("&totalRows_morning=%d%s", $totalRows_morning, $queryString_morning);
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

   <title>Saveuer De France</title>

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
                                        <h5 class="media-heading"><strong>John Smith</strong>
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
                                        <h5 class="media-heading"><strong>John Smith</strong>
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
                                        <h5 class="media-heading"><strong>John Smith</strong>
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
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?php echo $row_Index['Emp_firstname']; ?> <?php echo $row_Index['Emp_lastname']; ?>  <b class="caret"></b></a>
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
            <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav side-nav">
                    <li >
                        <a href="empindex2.php"><i class="fa fa-fw fa-dashboard"></i> Dashboard</a>
                    </li>
                    <li >
                       
                        <a href="Viewcust.php"><i class="fa fa-fw fa-table"></i> View Customers</a>
                    </li>
                        <li class="active">
                        <a href="ViewBookings.php"><i class="fa fa-fw fa-table"></i> Breakfast Booking</a>
                    </li>
                    <li>
                        <a href="ViewBookings2.php"><i class="fa fa-fw fa-table"></i> Lunch Booking</a>
                    </li>
                    <li>
                        <a href="ViewBookings3.php"><i class="fa fa-fw fa-table"></i> Dinner Booking</a>
                    </li>
                    
                   
                    
                    
                    <li>
                        <a href="javascript:;" data-toggle="collapse" data-target="#demo"><i class="fa fa-fw fa-arrows-v"></i> Manage Customer Booking <i class="fa fa-fw fa-caret-down"></i></a>
                        <ul id="demo" class="collapse">
                         <li>
                        <a href="UpdateMorningBooking.php"><i class="fa fa-fw fa-edit"></i> Update Customers Breakfast Bookings</a>
                    </li>
                      <li>
                        <a href="UpdateLunchBooking.php"><i class="fa fa-fw fa-edit"></i> Update Customers Lunch Bookings</a>
                    </li>
                      <li>
                        <a href="UpdateDinnerBooking.php"><i class="fa fa-fw fa-edit"></i> Update Customers Dinner Bookings</a>
                    </li>
                    <li>
                        <a href="RemoveMorningBooking.php"><i class="fa fa-fw fa-edit"></i>  Remove Morning Session Booking</a>
                    </li>
                    <li>
                        <a href="RemoveLunchBooking.php"><i class="fa fa-fw fa-edit"></i>  Remove Lunch Session Booking</a>
                    </li>
                    <li>
                        <a href="RemoveDinnerBooking.php"><i class="fa fa-fw fa-edit"></i>  Remove Dinner Session Booking</a>
                    </li>
                        </ul>
                    </li>
                    
                  <li>
                        <a href="Admins.php"><i class="fa fa-user"></i> Adminstrator Only </a>
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
                           Morning Session Booking
                        </h1>
                        <ol class="breadcrumb">
                            <li>
                                <i class="fa fa-dashboard"></i>  <a href="Admins.php">Dashboard</a>
                            </li>
                            <li class="active">
                                <i class="fa fa-table"></i> Morning Session Booking
                            </li>
                        </ol>
                    </div>
                </div>
                <!-- /.row -->

               <p>Showing <?php echo ($startRow_morning) ?> to <?php echo min($startRow_morning + $maxRows_morning, $totalRows_morning) ?> of <?php echo $totalRows_morning ?> Bookings</p>
               <table class="table"> 
                
                <thead> 
                    <tr> 
                        <th>Booking ID</th> <th>Customer ID</th>  <th>Party Size </th><th>Date</th><th>Comments</th>
                    </tr> 
                </thead> 
                <tbody>
                <?php if ($totalRows_morning > 0) { // Show if recordset not empty ?>
                      <?php do { ?>
                  <tr class="active">
                    
                        <td><?php echo $row_morning['Breakfast_ID']; ?></td>
                        <td><?php echo $row_morning['Customer_ID']; ?></td>
                        
                        <td><?php echo $row_morning['Party_size']; ?></td>
                        
                        <td><?php echo $row_morning['Date']; ?></td>
                        <td><?php echo $row_morning['Comment']; ?></td>
                        
                  </tr><?php } while ($row_morning = mysql_fetch_assoc($morning)); ?>
                      <?php } // Show if recordset not empty ?>
                </tbody> 
              </table>
              
              <div class="row text-center">
            <div class="col-lg-12">
                <ul class="pagination">
                  <?php if ($pageNum_morning > 0) { // Show if not first page ?>
  <li>
    <a href="<?php printf("%s?pageNum_morning=%d%s", $currentPage, max(0, $pageNum_morning - 1), $queryString_morning); ?>" >&laquo; Previous Page</a>
  </li>
  <?php } // Show if not first page ?>
  <?php if ($pageNum_morning < $totalPages_morning) { // Show if not last page ?>
  <li> <a href="<?php printf("%s?pageNum_morning=%d%s", $currentPage, min($totalPages_morning, $pageNum_morning + 1), $queryString_morning); ?>">Next Page &raquo;</a> </li>
  <?php } // Show if not last page ?>
                </ul>
            </div>
        </div>
                    
                </div>
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
mysql_free_result($Index);

mysql_free_result($morning);
?>
