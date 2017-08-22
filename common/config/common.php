<?php
use yii\helpers\FileHelper;
use yii\helpers\ArrayHelper;
use yii\base\Security;
use yii\web\UploadedFile;
use common\models\Deal;
use common\models\Coupon;
use common\models\Store;
use common\models\Category;
use common\models\DealLike;
use common\models\User;
use common\models\Cmspages;
//Image Libraries
use yii\imagine\Image;
use Imagine\Gd;
use Imagine\Image\Box;
use Imagine\Image\BoxInterface;


ini_set('default_charset', 'utf-8');
function prd($data=null){
    echo '<pre>';   
    print_r($data);
    echo '</pre>';  
    exit;
    
}
function pr($data=null){
    echo '<pre>';   
    print_r($data);
    echo '</pre>';  
    
}
function p($data,$exit=1){
	echo '<pre>';	
	print_r($data);
	echo '</pre>';	
    if(!empty($exit))
    {
        exit;
    }
}


function sstr($string, $start, $length) {
    if (strlen($string) > $length) {
        return mb_substr($string, $start, $length, 'UTF-8') . "...";
    } else {
        return $string;
    }
}

function uploadFile($file_element,$file_element_name,$extra=array()){
    $return=false;

    // p(Yii::$app->security->generateRandomString(10));
    if(!empty($file_element) && !empty($file_element_name)){
        //create Instance
        if(empty($extra['multiple'])){
            $uploaded_file = UploadedFile::getInstance($file_element, $file_element_name);
        }else{
            $uploaded_file = UploadedFile::getInstances($file_element, $file_element_name);    
        }
        
        if(!empty($uploaded_file)){
            //Get File Configs
            $file_config=getCustomConfigItem("upload_config",$file_element_name);
            $upload_file_folder=Yii::getAlias('@uploads')."/".(empty($file_config['upload_folder']) ? "other" : $file_config['upload_folder'])."/"; 
            if(!is_array($uploaded_file))
            {
                $uploaded_file=[$uploaded_file];
            }
            foreach($uploaded_file as $file)
            {
                $file_name=((empty($file_config['file_name_element']) || empty($file_element->$file_config['file_name_element'])) ? Yii::$app->security->generateRandomString(5).strtotime(date("YmdHis")) : $file_element->$file_config['file_name_element']).".".$file->extension;

                //Create Folder
                FileHelper::createDirectory($upload_file_folder);

                //Upload File
                if($file->saveAs($upload_file_folder.$file_name) && array_key_exists("current_file",$extra) && is_file($upload_file_folder.$extra['current_file']) && $extra['current_file'] != $file_name)
                {
                    unlink($upload_file_folder.$extra['current_file']);
                }

                if(array_key_exists('resize_arr', $file_config) && !empty($file_config['resize_arr'])){
                    foreach($file_config['resize_arr'] as $resize_element){
                        $resized_file_folder=$upload_file_folder."/".$resize_element['folder_name']."/";
                        $resized_file=$resized_file_folder.$file_name;

                        //Create Folder
                        FileHelper::createDirectory($resized_file_folder);

                        Image::getImagine()->open($upload_file_folder.$file_name)->thumbnail(new Box($resize_element['width'], $resize_element['height']))->save($resized_file, ['quality' => 90]);

                        if(array_key_exists("current_file",$extra) && is_file($resized_file_folder.$extra['current_file']) && $extra['current_file'] != $file_name)
                        {
                            unlink($resized_file_folder.$extra['current_file']);
                        }
                    }
                }

                $return[]=$file_name;
            }
            
        }
        if(empty($extra['multiple'])){
            $return=$return[0];
        }
        
    }
    // p("hello");
    return $return;
}

function getUploadedFile($file_name,$file_config,$file_type="original",$default=false) {
        $return =false;
        if(!empty($file_name) && !empty($file_config)){
            $file_config=getCustomConfigItem("upload_config",$file_config);
            $file_folder=(empty($file_config['upload_folder']) ? "other" : $file_config['upload_folder'])."/";
            if($file_type!="original" && !empty($file_config['resize_arr']) && array_key_exists($file_type, $file_config['resize_arr'])){
                $file_folder.=$file_config['resize_arr'][$file_type]['folder_name']."/";
            }
            if(is_file(Yii::getAlias('@uploads')."/".$file_folder.$file_name)){
                $return="@web/uploads/".$file_folder.$file_name;
            }
        }
        $default_image=$file_type=="original" ? "default.png" : $file_type."_default.png";
        $return = empty($return) && !empty($default) ? '@web/images/default/'.$default_image : $return;
        return $return;         
    }

function removeUploadedFiles($file_names,$file_config) {
        $return =false;
        if(!is_array($file_names)){
            $file_names=[$file_names];
        }
        if(!empty($file_names) && !empty($file_config)){
            $file_config=getCustomConfigItem("upload_config",$file_config);
            $file_folder=Yii::getAlias('@uploads')."/".(empty($file_config['upload_folder']) ? "other" : $file_config['upload_folder'])."/";
            foreach($file_names as $file_name){
                if(is_file($file_folder.$file_name)){
                    unlink($file_folder.$file_name);               
                }
                if(!empty($file_config['resize_arr'])) {
                    foreach($file_config['resize_arr'] as $resize_element){
                        $resized_file_folder=$file_folder."/".$resize_element['folder_name']."/";
                        if(is_file($resized_file_folder.$file_name)){
                            unlink($resized_file_folder.$file_name);               
                        }
                        
                    }
                }
            }
            return true;
        }
        return $return;         
    }

function getCustomConfigItem($element,$sub_element=""){
    $config_element=Yii::$app->params[$element];
    return empty($sub_element) ? $config_element : $config_element[$sub_element];
}

function myCurrencyFormat($amount){
    $currency_symbol="$";
    $return="0.00";
    if(!is_nan($amount)){
        $return=number_format($amount,2);
    }
    return $currency_symbol.$return;
}



function getDeals($extra_conditions=[],$limit="5",$other=[]){
    $deal=new Deal;
    $deal_param_arr=["extra_conditions"=>$extra_conditions,'limit'=>$limit];
    if(!empty($other)){
        $deal_param_arr=array_merge($deal_param_arr,$other);
    }
    $deal_records=$deal->getDeals($deal_param_arr);
    return $deal_records;//['deals'];    
}

function getCoupons($extra_conditions=[],$limit="5",$order_by=['coupon_modified_at' => SORT_DESC]){
    $conditions=[
        'coupon_status' => Coupon::STATUS_ACTIVE,
    ];
    if(!empty($extra_conditions)){
        $conditions = ArrayHelper::merge($conditions, $extra_conditions);
    }
    $data=Coupon::find()->where($conditions);
    if(!empty($limit)){
        $data->limit($limit);
    }
    if(!empty($order_by)){
        $data->orderBy($order_by);
    }
    return $data->all();
}

function getCategories($extra_conditions=[],$limit="5",$order_by=['category_modified_at' => SORT_DESC]){
    $conditions=[
        'category_status' => Category::STATUS_ACTIVE,
    ];
    if(!empty($extra_conditions)){
        $conditions = ArrayHelper::merge($conditions, $extra_conditions);
    }
    $data=Category::find()->where($conditions);
    if(!empty($limit)){
        $data->limit($limit);
    }
    if(!empty($order_by)){
        $data->orderBy($order_by);
    }
    // p($data->createCommand()->getRawSql());
    return $data->all();
}

function getStores($extra_conditions=[],$limit="5",$order_by=['store_modified_at' => SORT_DESC]){
    $conditions=[
        'store_status' => Store::STATUS_ACTIVE,
    ];
    if(!empty($extra_conditions)){
        $conditions = ArrayHelper::merge($conditions, $extra_conditions);
    }
    $data=Store::find()->where($conditions);
    if(!empty($limit)){
        $data->limit($limit);
    }
    if(!empty($order_by)){
        $data->orderBy($order_by);
    }
    return $data->all();
}

function getDealLikes($deal_id,$initial_like=0){
    $total_likes=$initial_like;
    if(!empty($deal_id)){
        $total_likes+=DealLike::find()->where(["like_deal"=>$deal_id,"like_status"=>DealLike::STATUS_ACTIVE])->count();
    }
    return $total_likes;
}

function getPublicDateFormat($date,$format=""){
    $return="";
    $format=empty($format) ? getCustomConfigItem('public_date_format') : $format;
    if(!empty($date) && $date != "0000-00-00") {

        $return=date($format,strtotime($date));

    }
    return $return;

}

function mailListUpdate($userData,$subscribe = 1){

    $listId = Yii::$app->params['listId'];

    $MailChimp = new \DrewM\MailChimp\MailChimp(Yii::$app->params['mailChimpApiKey']);        

    $email = $userData['email'];

    $email = strtolower($email);

    $hashKey = md5($email);

    //CHECK USER EXIST IN LIST
    $result = $MailChimp->get('lists/'.$listId.'/members/'.$hashKey);

    $status = ($subscribe == 1)?'subscribed':'unsubscribed';
    
    $reqData = [
        'email_address' => $email,
        'status' => $status
    ];   

    if($result['status'] == 404){
        //ADD SUBSCRIBER
        if(isset($userData['first_name']) && isset($userData['last_name']) && !empty($userData['first_name']) && !empty($userData['last_name'])){
            $reqData['merge_fields'] = ['FNAME' => $userData['first_name'],'LNAME' => $userData['last_name']];
        }
        return $MailChimp->post('lists/'.$listId.'/members',$reqData);
    }else{
        //UPDATE SUBSCRIBER
        return $MailChimp->put('lists/'.$listId.'/members/'.$hashKey,$reqData);
    }    

}
function getDealExpireTimer($deal){
    $return="";
    if(!empty($deal->GmtDealExpiry)) {
        $expiry_timestamp=strtotime($deal->GmtDealExpiry);
        $current_timestamp=strtotime(date("Y-m-d H:i:s"));
        $diff=($expiry_timestamp-$current_timestamp)/(60*60);
        if($diff<24 && $diff > 0){
            $seconds_remaining=$expiry_timestamp - $current_timestamp;
            $return = '<div class="timer-quick text-right" data-seconds-left="'.$seconds_remaining.'"><span class="glyphicon glyphicon-time"></span> </div>';
        }
    }
    return $return;
}

function footerPagesLink(){
    $faq_page=['faq'=>'FAQ'];
    $cms_pages=ArrayHelper::map(Cmspages::find()->orderBy(['cms_page_title'=>SORT_ASC])->all(),'cms_page_slug','cms_page_title');
    return array_chunk(array_merge($faq_page,$cms_pages), 3,true);
    
}

function getSideBlock($block_arr=['hot_deals','side_ads_block','coupons','categories']){
    
    $return='<div class="col-md-3 col-sm-4 col-xs-12"><div class="row">';
    if(in_array('side_ads_block', $block_arr)){
        $return.=Yii::$app->view->render('@app/views/partials/side_ads_block');
    }
    if(in_array('hot_deals', $block_arr)){
        $hot_deals=getDeals(['deal_hot_deal'=>1],'5',['with'=>['store'],'returnArray'=>true]);
        $return.=Yii::$app->view->render('@app/views/partials/deal_side_block', [
                  'deals' => $hot_deals,
                  'title' => "Hot Deals",
                  'link' => "hot-deals",
                  'icon'  => "fa fa-fire"
        ]);
    }
    if(in_array('coupons', $block_arr)){
        $coupons=getCoupons();
        $return.=Yii::$app->view->render('@app/views/partials/coupon_side_block', [
                'coupons' => $coupons,
            ]);
    }
    if(in_array('categories', $block_arr)){
        $categories=getCategories(['category_top_category'=>1,'category_parent'=>'0']);
        $return.=Yii::$app->view->render('@app/views/partials/category_side_block', [
                'categories' => $categories,
            ]);
    }
    $return.='</div></div>';
    return $return;
}