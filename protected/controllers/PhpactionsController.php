<?php

class PhpactionsController extends Controller {

    public function actions() {
        return array(
            //Home actions
            'closeshop' => 'application.controllers.home.CloseShopAction',
            'openshop' => 'application.controllers.home.OpenShopAction',
            //
            //reporting actions
            'export' => 'application.controllers.reporting.ExportAction',
            //
            //location actions
            'updatehours' => 'application.controllers.admin.location.UpdateHoursAction',
            'addlocation' => 'application.controllers.admin.location.AddLocationAction',
            'updatelocation' => 'application.controllers.admin.location.UpdateLocationAction',
            'getlocationinfo' => 'application.controllers.admin.location.GetLocationInfoAction',
            'deletelocation' => 'application.controllers.admin.location.DeleteLocationAction',
            //
            //holiday actions
            'addholiday' => 'application.controllers.admin.holidays.AddHolidayAction',
            'deleteholiday' => 'application.controllers.admin.holidays.DeleteHolidayAction',
            'holidayupdate' => 'application.controllers.admin.holidays.HolidayUpdateAction',
            //
            //cron job
            'cron'=>'application.controllers.cron.CronJobAction',
        );  
    }

// Uncomment the following methods and override them if needed
    /*
      public function filters()
      {
      // return the filter configuration for this controller, e.g.:
      return array(
      'inlineFilterName',
      array(
      'class'=>'path.to.FilterClass',
      'propertyName'=>'propertyValue',
      ),
      );
      }

      public function actions()
      {
      // return external action classes, e.g.:
      return array(
      'action1'=>'path.to.ActionClass',
      'action2'=>array(
      'class'=>'path.to.AnotherActionClass',
      'propertyName'=>'propertyValue',
      ),
      );
      }
     */
}