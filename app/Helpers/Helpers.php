<?php
/**
 * Created by PhpStorm.
 * User: himanshu
 * Date: 14/11/18
 * Time: 4:36 PM
 */
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;

/**
 * upload the images to the path passed and create the thumnail if required
 * @param UploadedFile $originalImage
 * @param string $path
 * @param bool $thumbnail_required
 * @param bool $remove_path
 * @return string
 */
function uploadImage(UploadedFile $originalImage, $path = '', $thumbnail_required = false, $remove_path = false){

    $time = time();

    $thumbnailImage = Image::make($originalImage);

    $thumbnailPath  = "{$path}/thumbnail/";

    $originalPath   = $path;

    if($remove_path) {
        File::deleteDirectory($originalPath);
    }

    if(!file_exists($thumbnailPath)) {
        mkdir($thumbnailPath, 0777, true);
    }

    $thumbnailImage->save($originalPath.$time.$originalImage->getClientOriginalName());

    if($thumbnail_required) {

        $thumbnailImage->resize(null, 150, function ($constraint) {
            $constraint->aspectRatio();
        });

        $thumbnailImage->save($thumbnailPath.$time.$originalImage->getClientOriginalName());
    }

    $file_name = $time.$originalImage->getClientOriginalName();

    return $file_name;
}

/**
 * returns the path for the users profile pic and if the $return_thumbnail is true than returns the path for the thumbnail image for the user
 * @param null $user_id
 * @param null $profile_pic
 * @param bool $return_thumbnail
 * @return bool|string
 */
function getUserProfileImageSrc($user_id = null, $profile_pic = null, $return_thumbnail = false){
    if($user_id) {
        $path = "user/{$user_id}/profile_pic/";
        if($return_thumbnail) {
            $path .= 'thumbnail/';
        }

        if($profile_pic) {
            return asset($path.$profile_pic);
        } else {
            return asset('assets/images/avatars/avatar1.png');
        }

    } else {
        return false;
    }
}

/**
 * returns the ids for the dependent childrens as well
 * since the super_admin can also make the changes to the leases added by his/her sub-users
 * @return array
 */

function getDependentUserIds(){
    $userIdsWithChildrens = Auth::user()->childrens->pluck('id')->toArray();
    if(auth()->user()->parent_id != 0){
        $userIdsWithChildrens = array_merge($userIdsWithChildrens, [auth()->user()->parent_id]);
    }
    return array_merge($userIdsWithChildrens, [auth()->user()->id]);
}

/**
 * calculate all the payment due dates provided the first payment due date and the last payment due date
 * @param $firt_payment_date Lease Asset First Payment Due Date
 * @param $last_payment_date Lease Asset Last Payment Due Date
 * @param $payment_payout Lease Asset Payment TimeOut
 * @param int $addMonths lease Asset Payment Months interval
 * @return array
 */
function calculatePaymentDueDates($firt_payment_date, $last_payment_date, $payment_payout, $addMonths = 1){
    //check if the payments are going to be monthly
    //have to loop from the first payment start date till the last payment end date
    $start_date = $firt_payment_date;
    $end_date  = $last_payment_date;
    $final_payout_dates = [];
    $i = 1;
    while (strtotime($start_date) <= strtotime($end_date)) {
        if($payment_payout == 1) {
            //means that the payment is going to be made at the start of the interval
            $start_date = \Carbon\Carbon::parse($start_date)->format('Y-m-d');
            $month = \Carbon\Carbon::parse($start_date)->format('F');
            $current_year  = \Carbon\Carbon::parse($start_date)->format('Y');
            $final_payout_dates[$current_year][$month][$start_date] =  $start_date;
            $start_date = \Carbon\Carbon::parse($start_date)->addMonth($addMonths)->format('Y-m-d');

            if(strtotime($start_date) <= strtotime($end_date)) {
                $month = \Carbon\Carbon::parse($end_date)->format('F');
                $current_year  = \Carbon\Carbon::parse($end_date)->format('Y');
                $date = \Carbon\Carbon::parse($end_date)->format('Y-m-d');
                $final_payout_dates[$current_year][$month][$date] =  $date;
            }

        } else if($payment_payout == 2){
            //means that the payment is going to be made at the end of the interval
            if($i ==1 ) {
                //will not increase by 1 month as the first payment date should be start_date
                $start_date = \Carbon\Carbon::parse($start_date)->format('Y-m-d');
            } else {
                $start_date = \Carbon\Carbon::parse($start_date)->addMonth($addMonths)->format('Y-m-d');
            }

            if(strtotime($start_date) <= strtotime($end_date)) {
                $month = \Carbon\Carbon::parse($start_date)->format('F');
                $current_year  = \Carbon\Carbon::parse($start_date)->format('Y');
                $final_payout_dates[$current_year][$month][$start_date] =  $start_date;
            } else {
                $month = \Carbon\Carbon::parse($end_date)->format('F');
                $current_year  = \Carbon\Carbon::parse($end_date)->format('Y');
                $date = \Carbon\Carbon::parse($end_date)->format('Y-m-d');
                $final_payout_dates[$current_year][$month][$date] =  $date;
            }
        }
        $i++;
    }
    return $final_payout_dates;
}

/**
 * calculate the payment due dates for any payment instance
 * @param \App\LeaseAssetPayments $payment
 * @return array
 */
function calculatePaymentDueDatesByPaymentId(\App\LeaseAssetPayments $payment){
    $final_payout_dates = [];
    //calculate all the due dates here from the start date till the end date...
    if($payment->payment_interval == 1) {
        //check if the payments are going to be One-Time
        if($payment->payout_time == 1) {
            //means that the payment is going to be made at the start of the interval
            $month = \Carbon\Carbon::parse($payment->first_payment_start_date)->format('F');
            $current_year  = \Carbon\Carbon::parse($payment->first_payment_start_date)->format('Y');
            $final_payout_dates[$current_year][$month][$payment->first_payment_start_date] =  $payment->first_payment_start_date;
        } else if($payment->payout_time == 2){
            //means that the payment is going to be made at the end of the interval
            $month = \Carbon\Carbon::parse($payment->last_payment_end_date)->format('F');
            $current_year  = \Carbon\Carbon::parse($payment->last_payment_end_date)->format('Y');
            $final_payout_dates[$current_year][$month][$payment->last_payment_end_date] =  $payment->last_payment_end_date;
        }
    }

    switch ($payment->payment_interval) {
        case 2:
            $final_payout_dates = calculatePaymentDueDates($payment->first_payment_start_date, $payment->last_payment_end_date,$payment->payout_time, 1);
            break;
        case 3:
            $final_payout_dates = calculatePaymentDueDates($payment->first_payment_start_date, $payment->last_payment_end_date,$payment->payout_time, 3);
            break;
        case 4:
            $final_payout_dates = calculatePaymentDueDates($payment->first_payment_start_date, $payment->last_payment_end_date,$payment->payout_time, 6);
            break;
        case 5:
            $final_payout_dates = calculatePaymentDueDates($payment->first_payment_start_date, $payment->last_payment_end_date,$payment->payout_time, 12);
            break;
    }
    return $final_payout_dates;
}

/**
 * generate the escalations that will be applied through out the year
 * @param array $data
 * @param \App\LeaseAssetPayments $payment
 * @param \App\Lease $lease
 * @param \App\LeaseAssets $asset
 * @return array
 */
function generateEsclationChart($data = [], \App\LeaseAssetPayments $payment, \App\Lease $lease, \App\LeaseAssets $asset){
    $effective_date = \Carbon\Carbon::parse($data['effective_from']);

    $escalation_basis = $data['escalation_basis'];
    $escalation_applied_consistently_annually = $data['is_escalation_applied_annually_consistently'];

    $start_date = $asset->accural_period; //start date with the free period
    $end_date   = $asset->getLeaseEndDate($asset); //end date based upon all the conditions

    $escalation_start_date = ($payment->using_lease_payment == '1')?\Carbon\Carbon::create(2019,01,01):\Carbon\Carbon::parse($start_date);
    $escalation_end_date   = \Carbon\Carbon::parse($end_date);

    $amount_to_consider    = 0;

    $years =  [];
    $start_year = $escalation_start_date->format('Y');
    $end_year = $escalation_end_date->format('Y');

    if($start_year == $end_year) {
        $years[] = $end_year;
    } else if($end_year > $start_year) {
        $years = range($start_year, $end_year);
    }

    for($m=1; $m<=12; ++$m ){
        $months[$m] = date('F', mktime(0, 0, 0, $m, 1));
    }

    $escalations = [];

    if($escalation_basis == '1' && $escalation_applied_consistently_annually == "yes"){
        //means the escalations will be applied annually and consistently and percentage basis is selected
        $escalation_percentage = $data['total_escalation_rate'];
        while ($start_year <= $end_year) {
            //always strat from Jan till December
            foreach ($months as $key => $month){
                if($effective_date->format('Y') > $start_year) {
                    //escalation percentage will be 0 here as the escalation is not applied upto this year
                    //need to check if the payment is need to be done on this month of this year
                    if(\Carbon\Carbon::parse($start_date)->format('Y') == $start_year && $key == \Carbon\Carbon::parse($start_date)->format('m')){
                        $amount_to_consider = $payment->payment_per_interval_per_unit;
                    }
                    $escalations[$start_year][$month] = ['percentage' => 0, 'amount' => $amount_to_consider];
                } else {
                    //compare the month here if the escalation is applied to current month as well
                    if($key == $effective_date->format('m')){
                        //yes the escalation is needed to be applied this month
                        if($amount_to_consider == 0){
                            $amount_to_consider = $payment->payment_per_interval_per_unit;
                        }

                        $amount_to_consider += ($amount_to_consider * $escalation_percentage)/100;
                        $escalations[$start_year][$month] = ['percentage' => $escalation_percentage, 'amount' => $amount_to_consider];
                    } else {
                        //no the escalation will not be applied on this year and this month
                        $escalations[$start_year][$month] = ['percentage' => 0, 'amount' => $amount_to_consider];
                    }
                }
            }
            $start_year = $start_year + 1; //increase annually here
        }
    }

//    die();

    return ['years' => $years,'months'    => $months,'escalations' => $escalations];
}