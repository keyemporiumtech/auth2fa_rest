<?php
App::uses("ActivityBS", "modules/authentication/business");

class ActivityUtility {

    static function getPivaById($id_activity = null) {
        if (empty($id_activity)) {
            return null;
        }
        $activityBS = new ActivityBS();
        $activityBS->acceptNull = true;
        $activity = $activityBS->unique($id_activity);

        return !empty($activity) ? $activity['Activity']['piva'] : null;
    }

    static function getIdActivityByPIVA($piva = null) {
        if (empty($piva)) {
            return null;
        }
        $activityBS = new ActivityBS();
        $activityBS->acceptNull = true;
        $activityBS->addCondition("piva", $piva);
        $activity = $activityBS->unique();

        return !empty($activity) ? $activity['Activity']['id'] : null;
    }
}