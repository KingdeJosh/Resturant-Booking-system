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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "booking")) {
  $insertSQL = sprintf("INSERT INTO booking (Cust_ID, Table_no, Item, Quantity, `Time`, Comments) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['customer'], "int"),
                       GetSQLValueString($_POST['tableno'], "int"),
                       GetSQLValueString($_POST['items'], "text"),
                       GetSQLValueString($_POST['Quantity'], "int"),
                       GetSQLValueString($_POST['time'], "date"),
                       GetSQLValueString($_POST['comments'], "text"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($insertSQL, $localhost) or die(mysql_error());

  $insertGoTo = "bookingsucc.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
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

mysql_select_db($database_localhost, $localhost);
$query_booking = "SELECT * FROM booking";
$booking = mysql_query($query_booking, $localhost) or die(mysql_error());
$row_booking = mysql_fetch_assoc($booking);
$totalRows_booking = mysql_num_rows($booking);

mysql_select_db($database_localhost, $localhost);
$query_Recordset1 = "SELECT * FROM `table`";
$Recordset1 = mysql_query($query_Recordset1, $localhost) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

mysql_select_db($database_localhost, $localhost);
$query_menu = "SELECT * FROM menu ORDER BY Price DESC";
$menu = mysql_query($query_menu, $localhost) or die(mysql_error());
$row_menu = mysql_fetch_assoc($menu);
$totalRows_menu = mysql_num_rows($menu);
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Modern Business - Start Bootstrap Template</title>
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
                <a class="navbar-brand" href="index.php">Start Bootstrap</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <a href="about.html">About</a>
                    </li>
                    <li>
                        <a href="services.html">Services</a>
                    </li>
                    <li>
                        <a href="contact.html">Contact</a>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Portfolio <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="portfolio-1-col.html">1 Column Portfolio</a>
                            </li>
                            <li>
                                <a href="portfolio-2-col.html">2 Column Portfolio</a>
                            </li>
                            <li>
                                <a href="portfolio-3-col.html">3 Column Portfolio</a>
                            </li>
                            <li>
                                <a href="portfolio-4-col.html">4 Column Portfolio</a>
                            </li>
                            <li>
                                <a href="portfolio-item.html">Single Portfolio Item</a>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Blog <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="blog-home-1.html">Blog Home 1</a>
                            </li>
                            <li>
                                <a href="blog-home-2.html">Blog Home 2</a>
                            </li>
                            <li>
                                <a href="blog-post.html">Blog Post</a>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Other Pages <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="full-width.html">Full Width Page</a>
                            </li>
                            <li>
                                <a href="sidebar.html">Sidebar Page</a>
                            </li>
                            <li>
                                <a href="faq.html">FAQ</a>
                            </li>
                            <li>
                                <a href="404.html">404</a>
                            </li>
                            <li>
                                <a href="pricing.html">Pricing Table</a>
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
                        <h1>Make Your Reservations </h1>
                        <h4>Call +233201232342 for special parties of people</h4>
                        <hr class="intro-divider">
                        <form action="<?php echo $editFormAction; ?>" name="booking" method="POST" id="booking">
                        <ul class="list-inline intro-social-buttons">
                                                   <li>
                                <label for="lastname" >Time</label>
                                
                            <?php $start = strtotime('6:00 AM'); $end = strtotime('10:59 PM');?>
                           <select class="form-control" name="party" id="peple">
                            <?php for($i = $start;$i<=$end;$i+=1800){?>
                            <option min="<?php echo date("h:iA")?>" value='<?php echo date('h:iA',$i); ?>'>
                                <?php echo date('h:iA',$i); ?></option>;<?php } ?></select>
                            </li>
                            <li>
                                <label for="lastname" >Date</label>
                                <input type="date" min="<?php echo date("Y-m-d")?>" max="<?php $d = strtotime("+1 Months");
                                echo date("Y-m-d",$d); ?>"class="form-control" name="time" id="time" >
                                
                            </li>
                            <li>
                                <label for="lastname" >Party Of</label>
                                <select class="form-control" name="party" id="peple"> <option>1</option> <option>2</option> <option>3</option> <option>4</option> <option>5</option> <option>6</option> </select>
                            </li>  
                               <li>
                                 <label for="lastname" >Food Items</label>
                                 
                                 <select class="form-control" name="items" id="items"><?php do { ?> <option><?php echo $row_menu['Item']; ?> - GH<?php echo $row_menu['Price']; ?></option> <?php } while ($row_menu = mysql_fetch_assoc($menu)); ?></select>
                                   
                            </li>
                            <li>
                                 <label for="lastname" >Quantity</label>
                                <select class="form-control" name="Quantity"> <option>1</option> <option>2</option> <option>3</option> <option>4</option> <option>5</option> <option>6</option> <option>7</option> <option>8</option> <option>9</option> <option>10</option></select>
                            </li>
                            
                           
                        </ul>
                        <ul class="list-inline intro-social-buttons">
                            <li>
                                <label for="name">Comment</label>  
                                <textarea  type="text" class="form-control" name="comments" id="Comments" placeholder="Input Additional food items and quatity" rows="3"></textarea>
                                
                            </li>

                        </ul>
                        <p>
                          <input name="tableno" type="hidden" value="<?php echo $row_Recordset1['Avalability']; ?>">
                          <input name="customer" type="hidden" value="<?php echo $row_Index['custid']; ?>"> 
                          <button type="submit" class="btn btn-warning"><span class="network-name">Reserve</span></button>
                        </p>
                        <input type="hidden" name="MM_insert" value="booking">
                        </form>  
                    </div>
                </div>
            </div>

                
                    
                    
                        <h1>Landing Page</h1>
                        <h3>A Template by Start Bootstrap</h3>
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
mysql_free_result($Index);

mysql_free_result($booking);

mysql_free_result($Recordset1);

mysql_free_result($menu);
?>
