<?php @session_start(); ?>
<?php require_once('Connections/localhost.php'); ?>
<?php require_once('Bill.php'); ?>
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
$colname_Checkspaces = "1";
if (isset($_POST['Date'])) {
  $colname_Checkspaces = $_POST['Date'];
}
mysql_select_db($database_localhost, $localhost);
$query_Checkspaces = sprintf("SELECT SUM(`Party_size`) FROM breakfast WHERE `Date` = %s", GetSQLValueString($colname_Checkspaces, "date"));
$Checkspaces = mysql_query($query_Checkspaces, $localhost) or die(mysql_error());
$row_Checkspaces = mysql_fetch_assoc($Checkspaces);
$totalRows_Checkspaces = mysql_num_rows($Checkspaces);
// *** Redirect if party size entered is greater than spaces left

$MM_flag="MM_insert";
if (isset($_POST[$MM_flag])) {
  $MM_dupKeyRedirect="Nospacesleft.php";
  $Booksize = $_POST['party'];
  $spacesleft = 20 - $row_Checkspaces['SUM(`Party size`)'];
  //if there is a row in the database, the username was found - can not add the requested username
  if($Booksize > $spacesleft){
    $MM_qsChar = "?";
    //append the username to the redirect page
    if (substr_count($MM_dupKeyRedirect,"?") >=1) $MM_qsChar = "&";
    $MM_dupKeyRedirect = $MM_dupKeyRedirect . $MM_qsChar ."requsername=".$Booksize;
    header ("Location: $MM_dupKeyRedirect");
    exit;
  }
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "morning session")) {
  $insertSQL = sprintf("INSERT INTO breakfast (Customer_ID, Party_size, Adults, Children, `Date`, `Comment`, Bill) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['customer'], "int"),
                       GetSQLValueString($_POST['Party_size'], "int"),
                       GetSQLValueString($_POST['Adult'], "int"),
                       GetSQLValueString($_POST['children'], "int"),
                       GetSQLValueString($_POST['Date'], "date"),
                       GetSQLValueString($_POST['comments'], "text"),
                       GetSQLValueString($_POST['Bill'], "int"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($insertSQL, $localhost) or die(mysql_error());

  $insertGoTo = "welcomepage.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_localhost, $localhost);
$query_Recordset2 = "SELECT * FROM breakfast";
$Recordset2 = mysql_query($query_Recordset2, $localhost) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);

$colname_Index = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_Index = $_SESSION['MM_Username'];
}
mysql_select_db($database_localhost, $localhost);
$query_Index = sprintf("SELECT * FROM customer WHERE Username = %s", GetSQLValueString($colname_Index, "text"));
$Index = mysql_query($query_Index, $localhost) or die(mysql_error());
$row_Index = mysql_fetch_assoc($Index);
$totalRows_Index = mysql_num_rows($Index);

$colname_bookmorn = "-1";
if (isset($_POST['Date'])) {
  $colname_bookmorn = $_POST['Date'];
}
mysql_select_db($database_localhost, $localhost);
$query_bookmorn = sprintf("SELECT * FROM breakfast WHERE `Date` = %s", GetSQLValueString($colname_bookmorn, "date"));
$bookmorn = mysql_query($query_bookmorn, $localhost) or die(mysql_error());
$row_bookmorn = mysql_fetch_assoc($bookmorn);
$totalRows_bookmorn = mysql_num_rows($bookmorn);


$Adult=$_POST['Adult'];
$Children=$_POST['children'];
$comments=$_POST['comments'];
$AdultChildren= $Children + $Adult;
$Partysize= $Children + $Adult;

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Saveurs De France</title>
    <link href="css/landing-page.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/modern-business.css" rel="stylesheet">

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
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i><?php echo $row_Index['Cust_firstname']; ?> <?php echo $row_Index['Cust_lastname']; ?><b class="caret"></b></a>
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

    <!-- Header Carousel -->
    
    <!-- Page Content -->
    <div class="container" style='background:white; padding-top:16px;'>

        <a name="about"></a>
      <div class="intro-header">
        <div class="container">

            <div class="row">
                <div class="col-lg-12">

                    <div class="intro-message">
                        <h1>Your Reservation Was Successful. </h1>
                        <h4>Please call our hotline +233201232342 to make special reservations</h4>
                     <hr class="intro-divider">
                        <form action="<?php echo $editFormAction; ?>" name="morning session" method="POST" id="booking">
                        <h5>Your Reservation Details Are Below :</h5>
                        <h5>Number of Adults :<?php echo $Adult ?></h5>
                        <h5>Number of Children :<?php echo $Children ?></h5>
                        <h5>Total Party Size :<?php echo $AdultChildren?></h5> 
                            <hr class="intro-divider">
                            <?php Bill::Calculatebill($Adult,$Children);
                            ?>  <hr class="intro-divider">
                            <ul class="list-inline intro-social-buttons">
                          <li>
                              
                            <input name="Date" type="Hidden" class="form-control" id="Date" placeholder="Enter Date" min= "<?php echo date("Y-m-d")?>" value="<?php echo $colname_Checkspaces ?>" <?php if (isset($_POST['Date'])) {
  $colname_Checkspaces = $_POST['Date'];
}?> >
                            </li>
                                <li>
                                <label for="Bill" ></label>
                                <input name="Bill" type="Hidden" class="form-control" id="Bill" value="<?php $billtotal= Bill::totalbill($Adult,$Children); echo $billtotal ?>">
                            </li>
                            <li>
                                <label for="Party Size" ></label>
                                <input name="Party_size"  type="Hidden" class="form-control" id="Party_size" value="<?php echo $Partysize?>">
                            </li>
                                                   <li>
                                <label for="Adults" ></label>
                                <input name="Adult" type="Hidden" class="form-control" id="Adult" value="<?php echo $Adult?>"> 
                            </li>
                             <li>
                                <label for="Children" > </label>
                                <input name="children" type="Hidden" class="form-control" id="children" value="<?php echo $Children?>">
                            </li>
                        </ul>
                        <ul class="list-inline intro-social-buttons">
                            <li>
                                <label for="name"></label>  
                                <input name="comments" type="hidden" class="form-control"  id="Comments" value="<?php echo $comments?>" rows="3">
                                
                          </li>

                        </ul>
                        <p>
                        
                        <input name="customer" type="hidden" value="<?php echo $row_Index['Customer_ID']; ?>">
                          <button type="submit" class="btn btn-warning"><span class="network-name"> DONE  </span></button>
                        </p>
                        <input type="hidden" name="MM_insert" value="morning session">
                        </form>
                          <h4>Kindly Click  <a href="Checkavailspaces.php">here</a> to check for spaces on other dates</h4>
                        <hr class="intro-divider">
                        <ul class="list-inline intro-social-buttons">
                            <li>
                                <a href="https://twitter.com/SBootstrap" class="btn btn-default btn-lg"><i class="fa fa-twitter fa-fw"></i> <span class="network-name">Twitter</span></a>
                            </li>
                            <li>
                                <a href="https://github.com/IronSummitMedia/startbootstrap" class="btn btn-default btn-lg"><i class="fa fa-github fa-fw"></i> <span class="network-name">Github</span></a>
                            </li>
                            <li>
                                <a href="#" class="btn btn-default btn-lg"><i class="fa fa-linkedin fa-fw"></i> <span class="network-name">Linkedin</span></a>
                            </li>
                        </ul>  
                    </div>
                </div>
            </div>

                
                    
                    
                                 
                    
                

        </div>
        <!-- /.container -->

    </div>
    <!-- /.intro-header -->

        <!-- Marketing Icons Section -->
       
        <!-- Footer -->
        

    </div>
    <!-- /.container -->

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <!-- Script to Activate the Carousel -->
    <script>
    $('.carousel').carousel({
        interval: 5000 //changes the speed
    })
    </script>

</body>

</html>
<?php
mysql_free_result($Recordset2);

mysql_free_result($Index);

mysql_free_result($bookmorn);

mysql_free_result($Checkspaces);
?>
