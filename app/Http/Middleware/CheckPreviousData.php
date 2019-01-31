<?php

namespace App\Http\Middleware;

use Closure;

class CheckPreviousData
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next , $step, $param_type, $param)
    {
     // dd($step);
        if($param_type =='asset_id'){

            $asset_id = $request->route($param);
            $asset = \App\LeaseAssets::query()->where('id', '=', $asset_id)->first();
            $lease_id = $asset->lease_id;
        }else{
            $lease_id = $request->route($param);
        }
        if($lease_id)
        {
           //Checking Assets for lease termination 
            $total_assets_termination_yes = \App\LeaseAssets::query()->where('lease_id', '=', $lease_id)->whereHas('terminationOption', function ($query) {
                $query->where('exercise_termination_option_available', '=', 'yes');
            })->count();
            $total_assets_termination_no = \App\LeaseAssets::query()->where('lease_id', '=', $lease_id)->whereHas('terminationOption', function ($query) {
                $query->where('exercise_termination_option_available', '=', 'yes');
            })->count();
            if($total_assets_termination_yes  && $step =='step6'){

                $this->setRedirect($total_assets_termination_yes, $step, $lease_id, $next, $request);
            }
            elseif($total_assets_termination_no && $step == 'step6'){

                $this->setRedirect($total_assets_termination_no, $step, $lease_id, $next, $request);
            }
            elseif($step == 'step11') {
                //Checking Assets for Select Low Value From 
                $category_excluded = \App\CategoriesLeaseAssetExcluded::query()->get();
                $category_excluded_id = $category_excluded->pluck('category_id')->toArray();

                $total_assets = \App\LeaseAssets::query()->where('lease_id', '=', $lease_id)->whereNotIn('specific_use', [2])
                ->whereHas('leaseDurationClassified',  function($query){
                $query->whereNotIn('lease_contract_duration_id',[1,2]);
                })->whereNotIn('category_id', $category_excluded_id)->count();

                $this->setRedirect($total_assets, $step, $lease_id, $next, $request);
            } elseif($step == 'step12') {
                //Checking Assets for Select Discount Rate
                
                 $own_assets = \App\LeaseAssets::query()->where('lease_id', '=', $lease_id)
                 ->where('specific_use',1)
                 ->whereNotIn('category_id',[8,5])
                 ->whereHas('leaseSelectLowValue',  function($query){
                         $query->where('is_classify_under_low_value', '=', 'no');
                  })
                 ->whereHas('leaseDurationClassified',  function($query){
                    $query->where('lease_contract_duration_id', '=', '3');
                  })->get();


                 $sublease_assets = \App\LeaseAssets::query()->where('lease_id', '=', $lease_id)
                 ->where('specific_use',2)
                 ->whereNotIn('category_id',[8,5])
                 ->whereHas('leaseSelectLowValue',  function($query){
                     $query->where('is_classify_under_low_value', '=', 'no');
                 })
                 ->whereHas('leaseDurationClassified',  function($query){
                    $query->where('lease_contract_duration_id', '=', '3');
                })->get();

                $total_assets = count($own_assets) + count($sublease_assets);

                $this->setRedirect($total_assets, $step, $lease_id, $next, $request);
                 
            } elseif($step == 'step13' || $step == 'step14'){
                 //Checking Assets for Laese Balence on Dec
                 
                 $total_assets =  \App\LeaseAssets::query()->where('lease_id', '=', $lease_id)->where('lease_start_date', '<', '2019-01-01')->count();

                $this->setRedirect($total_assets, $step, $lease_id, $next, $request);

            
            } elseif($step == 'step15'){
                  //Checking Assets for lease incentives
                  $total_assets = \App\LeaseAssets::query()->where('lease_id', '=', $lease_id)->where('lease_start_date','>=','2019-01-01')->count();
                $this->setRedirect($total_assets, $step, $lease_id, $next, $request);
            } elseif($step == 'step16'){
                  //Checking Assets for lease valuation
                    $own_assets = \App\LeaseAssets::query()->where('lease_id', '=', $lease_id)
                    ->where('specific_use',1)
                    ->whereHas('leaseSelectLowValue',  function($query){
                    $query->where('is_classify_under_low_value', '=', 'no');
                    })->whereHas('leaseDurationClassified',  function($query){
                    $query->where('lease_contract_duration_id', '=', '3');
                    })->whereNotIn('category_id',[5,8])->get();

                    $sublease_assets = \App\LeaseAssets::query()->where('lease_id', '=', $lease_id)
                    ->where('specific_use',2)
                    ->whereHas('leaseSelectLowValue',  function($query){
                    $query->where('is_classify_under_low_value', '=', 'no');
                    })->whereHas('leaseDurationClassified',  function($query){
                    $query->where('lease_contract_duration_id', '=', '3');
                    })->whereNotIn('category_id',[5,8])->get();
                    $total_assets = count($own_assets) + count($sublease_assets);
                $this->setRedirect($total_assets, $step, $lease_id, $next, $request);
            }
            elseif($step == 'step17'){
                
                //Checking Assets for lease valuation
                $own_assets = \App\LeaseAssets::query()->where('lease_id', '=', $lease_id)
                ->where('specific_use',1)
                ->whereHas('leaseSelectLowValue',  function($query){
                $query->where('is_classify_under_low_value', '=', 'no');
                })->whereHas('leaseDurationClassified',  function($query){
                $query->where('lease_contract_duration_id', '=', '3');
                })->whereNotIn('category_id',[5,8])->get();

                $sublease_assets = \App\LeaseAssets::query()->where('lease_id', '=', $lease_id)
                ->where('specific_use',2)
                ->whereHas('leaseSelectLowValue',  function($query){
                $query->where('is_classify_under_low_value', '=', 'no');
                })->whereHas('leaseDurationClassified',  function($query){
                $query->where('lease_contract_duration_id', '=', '3');
                })->whereNotIn('category_id',[5,8])->get();

                $total_assets = count($own_assets) + count($sublease_assets);

                $this->setRedirect($total_assets, $step, $lease_id, $next, $request);
            }
            else {
                $total_assets = \App\LeaseAssets::query()->where('lease_id', '=', $lease_id)->count();

                $this->setRedirect($total_assets, $step, $lease_id, $next, $request);
            }

        }
        return $next($request);
    }

    private function setRedirect($total_assets, $step, $lease_id, $next, $request){
        if($total_assets > 0) {
            //need to check if the data  for the step11 is present or not
            $confrim_steps = \App\LeaseCompletedSteps::query()->where('lease_id', '=', $lease_id)->where('completed_step','=', $step)->count();

            if($confrim_steps > 0){
                return $next($request);
            } else {
                abort(404);
            }
        } else {
            return $next($request);
        }
    }
}
