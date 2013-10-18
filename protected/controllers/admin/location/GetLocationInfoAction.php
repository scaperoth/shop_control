<?php

class GetLocationinfoAction extends CAction {

    public function run() {
            $request = $_POST['UpdateLocation'];
            $loc_id = $request['locationnameupdate'];
            
            $db_rows = Ips::model()->findAll();
            foreach($db_rows as $row){
                if(md5($row['ip_loc_id'])==$loc_id){
                    $return_data= array(
                        'ip'=>$row['ip_address'],
                        'name'=>$row['ip_compname']
                    );
                    break;
                }
            }
            echo json_encode($return_data);
        
    }

}

?>
