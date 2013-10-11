<?php

class ApiVarsFilter extends CFilter {
    
    protected function preFilter($filterChain) {
        $locations_query = Locations::model()->findAll();
        // This tells the filter chain $c to keep processing. 
        
        // logic being applied before the action is executed
        return true; // false if the action should not be executed
    }

    protected function postFilter($filterChain) {
        // logic being applied after the action is executed
    }

}

?>
