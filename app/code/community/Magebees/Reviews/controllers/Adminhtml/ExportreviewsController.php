<?php
class Magebees_Reviews_Adminhtml_ExportreviewsController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
       $this->loadLayout();
       $this->_title($this->__("Export Product Reviews"));
       $this->renderLayout();
    }
    public function exportAction()
    {
        $export_dir = "var/export";
        if(!is_dir($export_dir)){
            mkdir($export_dir, 0755, true);
        }
    
        $export_file_name = "exportreviews_".date('m-d-Y_h-i-s', time()).".csv";
        $reviews = Mage::getModel('review/review')->getResourceCollection()
                    ->addStoreData()
                    ->setDateOrder()
                    ->addRateVotes()
                    ->load();
		
		
		$reviewData = $reviews->getData();
		
		if(empty($reviewData)){
			$result = "<div id='messages'><ul class='messages'><li class='error-msg'><ul><li><span>No Record Found</span></li></ul></li></ul></div>";

			$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));  
		
		}else{
		
			$finaldata = array();    
			foreach($reviews as $review){
				$sku = Mage::getModel('catalog/product')->load($review->getEntity_pk_value())->getSku(); 

				if($sku){
					$storeIds = '';
					$storesData = $review->getStores();
					if(!empty($storesData)) {
						$y =0;    
						foreach($review->getStores() as $stores) {
							if($y < count($review->getStores())-1){
								$storeIds .= $stores.",";
							}else{
								$storeIds .= $stores;
							}

							$y++;
						}
					}    

					$reviewsummery = Mage::getModel('rating/rating')->getReviewSummary($review->getId()); 
					$reviewCnt = $reviewsummery->getCount();
					if($reviewCnt){
						$ratingsummery = ceil($reviewsummery->getSum() / ($reviewCnt));
					}else{
						$ratingsummery = "";
					}

					$ratingCollection = Mage::getModel('rating/rating_option_vote')
							->getResourceCollection()
							->setReviewFilter($review->getId());

					$rating_val = "";
					$option = "";
					$option_value = '';

					foreach($ratingCollection as $rating)   {
						$option =  $rating->getOptionId();                        
						$rating_val = $rating->getRatingId(); 

						if(!empty($option_value) && $option_value != '') 
							$option_value = $option_value."|".$rating_val.":".$option; 
						 else
							$option_value = $rating_val.":".$option;
					}                

					$c_id = $review->getCustomer_id();
					if(isset($c_id)){
						$customerData = Mage::getModel('customer/customer')->load($review->getCustomer_id())->getData();
						$customeremail = $customerData['email'];
					}else{
						$customeremail = "";
					}

					$statusId = $review->getStatus_id();
					if($statusId == "1"){
						$status = "Approved";
					}elseif($statusId == "2"){
						$status = "Pending";
					}else{
						$status = "Not Approved";
					}

					$csv_fields = array();        
					$c_id = "";
					$csv_fields['created_at'] = $review->getCreated_at();
					$csv_fields['sku'] = $sku;                
					$csv_fields['status'] = $status;
					$csv_fields['title'] = $review->getTitle();
					$csv_fields['detail'] = $review->getDetail();
					$csv_fields['nickname'] = $review->getNickname();
					$csv_fields['customer_email'] = $customeremail;
					$csv_fields['rating_summary'] = $ratingsummery;
					$csv_fields['option_id'] = $option_value;
					$csv_fields['store_ids'] = $storeIds;
					array_push($finaldata, $csv_fields);
				}    
			}

			$header = array();
			foreach ($finaldata as $data) {
				foreach(array_keys($data) as $k=>$v){
					$header[$v] = $v;
				}
			}

			$files = fopen("var/export/".$export_file_name, "a");
			fputCsv($files, array_keys($header));
			fclose($files);

			$files = fopen("var/export/".$export_file_name, "a");
			foreach ($finaldata as $data) {
					$o_data=array_fill_keys(array_values($header), '');
					foreach($data as $o_key=>$o_val)
					{
						if (in_array($o_key, $header)) {
							$o_data[$o_key]=$o_val;
						}
					}

					fputcsv($files, array_values($o_data));
			}

			fclose($files);

			$download_path = Mage::helper("adminhtml")->getUrl("adminhtml/exportreviews/downloadExportedFile/", array("file"=>$export_file_name));;
			$result = "";
			$result = "<div id='messages'><ul class='messages'><li class='success-msg'><ul><li><span>Generated csv File : <b style='font-size:12px'><a href='".$download_path."' target='_blank'>".$export_file_name."</a></b></span></li></ul></li></ul></div>";

			$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));    
		}	

    }
    
    public function downloadExportedFileAction()
    {
        
        $filename=Mage::app()->getRequest()->getParam('file');
        $filepath = Mage::getBaseDir('base').'/var/export/'.$filename;

        if (! is_file($filepath) || ! is_readable($filepath)) {
            throw new Exception();
        }

        $this->getResponse()
                ->setHttpResponseCode(200)
                ->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true)
                ->setHeader('Pragma', 'public', true)
                ->setHeader('Content-type', 'application/force-download')
                ->setHeader('Content-Length', filesize($filepath))
                ->setHeader('Content-Disposition', 'attachment' . '; filename=' . basename($filepath));
        $this->getResponse()->clearBody();
        $this->getResponse()->sendHeaders();
        readfile($filepath);
        return;
    }
    
    protected function _isAllowed()
    {
        return true;
    }
    
}
