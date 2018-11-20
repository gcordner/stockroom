<?php class Powr_Pack_Model_Resource_Setup extends Mage_Sales_Model_Resource_Setup
{
    public function addInstallationSuccessfulNotification(){
        $inboxModel = Mage::getModel('adminnotification/inbox');
        if(!method_exists($inboxModel,'addNotice')){
            return;
        }
        $message = "You have successfully installed Powr_Pack and can configure your widgets at Cms > Powr Pack";
        $inboxModel->addNotice(
            $message,
            $message,
            true
        );
    }
}