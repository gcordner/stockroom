<?php
class Magebees_Reviews_Adminhtml_ImportreviewsController extends Mage_Adminhtml_Controller_Action
{

    
    public function indexAction()
    {
       $this->loadLayout();
       $this->_title($this->__("Import Product Reviews"));
       $this->renderLayout();
    }
    
    public function importAction()
    {
        if(isset($_FILES['filename']['name']) && $_FILES['filename']['name'] != '') {
            $mappings = array();
            try {    
                $uploader = new Varien_File_Uploader('filename');
                $uploader->setAllowedExtensions(array('csv'));
                $uploader->setAllowRenameFiles(false);
                $uploader->setFilesDispersion(false);
                $path = Mage::getBaseDir('var') . DS . 'import'. DS;
                $filenames = preg_replace('/[^a-zA-Z0-9._]/s', '', $_FILES['filename']['name']);
                $path_parts = pathinfo($filenames);
                $filename = $path_parts['filename'].'_'.time().'.'.$path_parts['extension'];
                $uploader->save($path, $filename);
            } catch (Exception $e) {   
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/');
            }
            
            $filename = $path.$filename;
            
            $id = fopen($filename, 'r');
            $data = fgetcsv($id, filesize($filename)); 
             if(!$mappings)
               $mappings = $data;
         
            while($data = fgetcsv($id, filesize($filename))){
                try {
                    $converted_data['customer_email'] = '';
                    $converted_data['created_at'] = '';
                    foreach($data as $key => $value) {
                        $converted_data[$mappings[$key]] = addslashes($value);
                    }
                                            
                    $customer_email = $converted_data['customer_email']; 
                    $sku = $converted_data['sku']; 
                    $customerExist = Mage::getModel('customer/customer')
                            ->getCollection()
                            ->addAttributeToSelect('*')
                            ->addAttributeToFilter('email', $customer_email)
                            ->getFirstItem();
                    $productid = Mage::getModel('catalog/product')->getIdBySku($sku); 

                    if($productid){    
                        $customerDataexit = $customerExist->getData();
                        if(empty($customerDataexit))     {
                            $customerid = NULL; 
                        }else{
                            $customerid = $customerExist->getEntityId();
                        }
                    
                        if($converted_data['status'] == "Approved"){
                            $status = "1";
                        }elseif($converted_data['status'] == "Pending"){
                            $status = "2";
                        }else{
                            $status = "3";
                        }
                        
                        $stores = explode(',', $converted_data['store_ids']);
                        $_review = Mage::getModel('review/review')
                                ->setEntityPkValue($productid)
                                ->setEntityId(1)
                                ->setStatusId($status)
                                ->setTitle($converted_data['title'])
                                ->setDetail($converted_data['detail'])
                                ->setStores($stores)
                                ->setCustomerId($customerid)
                                ->setNickname($converted_data['nickname'])
                                ->setCreatedAt($converted_data['created_at'])
                                ->save();
                        if($converted_data['created_at']){    
                            Mage::getModel('review/review')->load($_review->getId())->setCreatedAt($converted_data['created_at'])->save(); 
                        }
                
                        if($converted_data['option_id']){
                            $arr_data = explode("|", $converted_data['option_id']);
                            if(!empty($arr_data)) {
                                foreach($arr_data as $each_data) {
                                    $arr_rating = explode(":", $each_data);
                                    if($arr_rating[1] != 0) {
                                            Mage::getModel('rating/rating')
                                            ->setRatingId($arr_rating[0])
                                            ->setReviewId($_review->getId())
                                            ->setCustomerId($customerid)
                                            ->addOptionVote($arr_rating[1], $productid);
                                    }
                                }                                        
                            }
     
                            $_review->aggregate();
                        }
                    }
                }catch (Exception $e) {   
                    $msg = $e->getMessage();
                    $result = "<div id='messages'><ul class='messages'><li class='error-msg'><ul><li><span>'".$msg."'</span></li></ul></li></ul></div>";
                    $this->getResponse()->setBody($result);    
                    return;
                }                
            }

            $result = "";
            $result = "<div id='messages'><ul class='messages'><li class='success-msg'><ul><li><span>Reviews were successfully Imported</span></li></ul></li></ul></div>";
            $this->getResponse()->setBody($result);    
        }        
    }
    
    protected function _isAllowed()
    {
        return true;
    }
}
