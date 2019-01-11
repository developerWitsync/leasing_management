<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class LeaseDurationClassified extends Model
{
    protected $expected_lease_date;
    protected $table = 'lease_duarion_classified';

    protected $fillable = [
        'asset_id',
        'lease_id',
        'lease_start_date',
        'lease_end_date',
        'lease_contract_duration_id',
        'created_at',
        'updated_at'
    ];

    /**
     * Fetch the expected lease end date for a particular lease asset based upon all the conditions on the sheet NL8.1
     * @param LeaseAssets $asset
     * @return mixed
     */
    public function getExpectedLeaseEndDate(LeaseAssets $asset){
        if($asset->purchaseOption && $asset->purchaseOption->purchase_option_exerecisable == 'yes'){
            $lease_end_date =   $asset->purchaseOption->expected_lease_end_date;
        } else if($asset->renewableOptionValue && $asset->renewableOptionValue->is_reasonable_certainity_option == 'yes'){
            $lease_end_date =   $asset->renewableOptionValue->expected_lease_end_Date;
        } else if($asset->terminationOption && $asset->terminationOption->exercise_termination_option_available == 'yes'){
            $lease_end_date =   $asset->terminationOption->lease_end_date;
        } else {
            $lease_end_date = $asset->lease_end_date;
        }
        $this->expected_lease_date  =  $lease_end_date;
        return $this->expected_lease_date;
    }

    /**
     * calculates the lease asset classification on the basis of the expected lease end date
     * @todo need to ask that which start date we have to consider accural date or lease start date
     * @param LeaseAssets $asset
     * @return mixed
     */
    public function getLeaseAssetClassification(LeaseAssets $asset){
        $lease_start_date = Carbon::parse($asset->lease_start_date);
        $base_date = Carbon::create(2019, 01,01);

        $end_date = Carbon::parse($this->expected_lease_date);

        if($lease_start_date->lessThan($base_date)) {
            //means that the lease start date is prior to base date
            $start_date = $base_date;
        } else {
            //means that the lease start date is after base date
            $start_date = $lease_start_date;
        }
        //calculate the difference in months
        $diff_in_months = $end_date->diffInMonths($start_date);
        $classification = LeaseContractDuration::query()->whereRaw("(`lower_limit` <= ?) AND (`upper_limit` >= ? OR upper_limit is NULL)")->setBindings([$diff_in_months, $diff_in_months])->first();
        return $classification->id;
    }

    public function getLeaseClassification(){
        return $this->belongsTo('App\LeaseContractDuration', 'lease_contract_duration_id', 'id');
    }
}
