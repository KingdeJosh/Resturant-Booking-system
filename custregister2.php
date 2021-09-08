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

// *** Redirect if username exists
$MM_flag="MM_insert";
if (isset($_POST[$MM_flag])) {
  $MM_dupKeyRedirect="custregister2error.php";
  $loginUsername = $_POST['Username'];
  $LoginRS__query = sprintf("SELECT Username FROM customer WHERE Username=%s", GetSQLValueString($loginUsername, "text"));
  mysql_select_db($database_localhost, $localhost);
  $LoginRS=mysql_query($LoginRS__query, $localhost) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);

  //if there is a row in the database, the username was found - can not add the requested username
  if($loginFoundUser){
    $MM_qsChar = "?";
    //append the username to the redirect page
    if (substr_count($MM_dupKeyRedirect,"?") >=1) $MM_qsChar = "&";
    $MM_dupKeyRedirect = $MM_dupKeyRedirect . $MM_qsChar ."requsername=".$loginUsername;
    header ("Location: $MM_dupKeyRedirect");
    exit;
  }
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form")) {
  $insertSQL = sprintf("INSERT INTO customer (Cust_firstname, Cust_lastname, Contact_no, Username, Password, Address) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['Firstname'], "text"),
                       GetSQLValueString($_POST['Lastname'], "text"),
                       GetSQLValueString($_POST['tel'], "int"),
                       GetSQLValueString($_POST['Username'], "text"),
                       GetSQLValueString($_POST['Password'], "text"),
                       GetSQLValueString($_POST['Address'], "text"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($insertSQL, $localhost) or die(mysql_error());

  $insertGoTo = "custlogin2.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_localhost, $localhost);
$query_CustLogin = "SELECT * FROM customer";
$CustLogin = mysql_query($query_CustLogin, $localhost) or die(mysql_error());
$row_CustLogin = mysql_fetch_assoc($CustLogin);
$totalRows_CustLogin = mysql_num_rows($CustLogin);
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
    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
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
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Other Pages <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            
                            
                            <li>
                                <a href="custlogin2.php">Sign In</a>
                            </li>
                            <li>
                                <a href="custregister2.php">Sign Up</a>
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
    <div class="container" style='background:white; '>

        <!-- Page Heading/Breadcrumbs -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Sign 
                    <small>Up</small></h1>
                <ol class="breadcrumb">
                    <li><a href="index.php">Home</a>

                    </li>
                    <li><a href="custlogin2.php">Sign In</a>
                        
                    </li>
                    <li class="active">Register</li>
                </ol>
            </div>
        </div>
        <!-- /.row -->

        <!-- Content Row -->
      
        <!-- /.row -->
        

        <!-- Contact Form -->
        <!-- In order to set the email address and subject line for the contact form go to the bin/contact_me.php file. -->
        <div class="row">
            <div class="box">
                <div class="col-lg-12">
                    
                    <p>If you do not have an account register now to book best table for your event</p>
                    <form method="POST" action="<?php echo $editFormAction; ?>" name="form" >
                        <div class="row">

                           <div class="form-group col-lg-4">
                                <label>First Name</label>
                                <input name="Firstname" type="text" id="Firstname" required data-validation-required-message="Please enter your message"  class="form-control">
                            </div>
                            <div class="form-group col-lg-4">
                                <label> Last Name</label>
                                <input name="Lastname" type="text" id="Lastname" required data-validation-required-message="Please enter your message" class="form-control">
                            </div>
                            
                            <div class="form-group col-lg-4">
                                <label>Phone Number</label>
                                <input name="tel"  id="tel" type="text" onkeypress="phoneno()"    class="form-control">
                            </div>
                            <div class="form-group col-lg-4">
                                <label> Username</label>
                                <input name="Username" type="text" id="Username" required data-validation-required-message="Please enter your message" class="form-control">
                            </div>
                            <div class="form-group col-lg-4">
                                <label>Password</label>
                                <input name="Password" required data-validation-required-message="Please enter your message" id="Password" type="password" class="form-control">
                            </div>
                           
                            <div class="form-group col-lg-12">
                                   <input type="hidden" name="save" value="contact">                                <button id="Submit" type="submit" class="btn btn-default">Sign Up</button>
                            </div>
                        </div>
                        <input type="hidden" name="MM_insert" value="form">
                    </form>
                   
                </div>
            </div>
        </div>
        <!-- /.row -->

        <hr>

        <!-- Footer -->
        <footer>
            <div class="row">
                <div class="col-lg-12">
                    <p>Copyright &copy; Saveurs De France 2015</p>
                </div>
            </div>
        </footer>

    </div>
    <!-- /.container -->
<script type="text/javascript">
function phoneno(){$('#tel').keypress(function(e){
var a=[];
var k = e.which;
for(i = 48; i < 58; i++)
  a.push(i);
if(!(a.indexOf(k)>=0))
  e.preventDefault();

});
}
</script>
    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
     <script src="js/jqBootstrapValidation.js"></script>


    <!-- Contact Form JavaScript -->
    <!-- Do not edit these files! In order to set the email address and subject line for the contact form go to the bin/contact_me.php file. -->
    

</body>

</html>
<?php
mysql_free_result($CustLogin);
?>
