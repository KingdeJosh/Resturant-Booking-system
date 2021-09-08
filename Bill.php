<?php
class Bill{
      public static function Calculatebill($Adults,$Children){
$Adultsprice = $Adults * 100;
$Childrenprice = $Children * 65;
$Totalprice =$Adultsprice + $Childrenprice;
      $result ="Price to pay for Adults : GHC ". $Adultsprice . " <br /> ". 
               "Price to pay for children : GHC ". $Childrenprice ." <br /> ".
               "Total Price to be paid : GHC " .$Totalprice;
          echo $result;
          return $result;


  
  }
      public static function totalbill($Adults,$Children){
$Adultsprice = $Adults * 100;
$Childrenprice = $Children * 65;
$Totalprice =$Adultsprice + $Childrenprice;
      
         
          return $Totalprice;


  
  }
      public static function Calculatebilllunch($Adults,$Children){
$Adultsprice = $Adults * 110;
$Childrenprice = $Children * 70;
$Totalprice =$Adultsprice + $Childrenprice;
      $result ="Price to pay for Adults : GHC ". $Adultsprice . " <br /> ". 
               "Price to pay for children : GHC ". $Childrenprice ." <br /> ".
               "Total Price to be paid : GHC " .$Totalprice;
          echo $result;
          return $result;


  
  }
      public static function totalbilllunch($Adults,$Children){
$Adultsprice = $Adults * 110;
$Childrenprice = $Children * 70;
$Totalprice =$Adultsprice + $Childrenprice;
      
         
          return $Totalprice;


  
  }
      public static function Calculatebilldinner($Adults,$Children){
$Adultsprice = $Adults * 120;
$Childrenprice = $Children * 75;
$Totalprice =$Adultsprice + $Childrenprice;
      $result ="Price to pay for Adults : GHC ". $Adultsprice . " <br /> ". 
               "Price to pay for children : GHC ". $Childrenprice ." <br /> ".
               "Total Price to be paid : GHC " .$Totalprice;
          echo $result;
          return $result;


  
  }
      public static function totalbilldinner($Adults,$Children){
$Adultsprice = $Adults * 120;
$Childrenprice = $Children * 75;
$Totalprice =$Adultsprice + $Childrenprice;
      
         
          return $Totalprice;


  
  }
    
}
?>