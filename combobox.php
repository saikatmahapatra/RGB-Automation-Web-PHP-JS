<?php
$array = array();
if ($_GET['_name'] == 'notcm') 
{
                if($_GET['_value']!=""){
                $incrVal=$_GET['_value']*6;
                        for($i=1;$i<=$incrVal;$i++){
                        $array[] = array($i => $i);
                        }
                }else{
                        $incrVal=6;
                        for($i=1;$i<=$incrVal;$i++){
                        $array[] = array($i => $i);
                        }
                }
}
echo json_encode( $array );
?>
