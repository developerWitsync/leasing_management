<?php

namespace App\Http\Middleware;

use App\Lease;
use Closure;
use Illuminate\Contracts\Logging\Log;

class CheckPreviousData
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next, $step, $param_type, $param)
    {

        \Log::info('Checking ----- '. $step. ' On URL ------- '. $request->route()->getName());
        if ($param_type == 'asset_id') {
            $asset_id = $request->route($param);
            $asset = \App\LeaseAssets::query()->where('id', '=', $asset_id)->first();
            $lease_id = $asset->lease_id;
        } else {
            $lease_id = $request->route($param);
        }

        if ($lease_id) {
            $lease = Lease::query()->findOrFail($lease_id);
            if($lease->assets->count() == 0){abort(403);}
        }

        if($step == 'step8') {
            $total_assets_termination_no = \App\LeaseAssets::query()->where('lease_id', '=', $lease_id)->whereHas('terminationOption', function ($query) {
                $query->where('lease_termination_option_available', '=', 'yes');
                $query->where('exercise_termination_option_available', '=', 'no');
            })->count();

            if($total_assets_termination_no == 0){
                $step = 'step6';
            } else {
                $step = 'step7';
                if($this->verifyStep($step, $lease_id)){
                    $step = 'step8';
                }
            }
        }

        if($step == 'step11') {
            $category_excluded = \App\CategoriesLeaseAssetExcluded::query()->get();
            $category_excluded_id = $category_excluded->pluck('category_id')->toArray();

            $total_assets = \App\LeaseAssets::query()->where('lease_id', '=', $lease_id)->whereNotIn('specific_use', [2])
                ->whereHas('leaseDurationClassified', function ($query) {
                    $query->whereNotIn('lease_contract_duration_id', [1, 2]);
                })->whereNotIn('category_id', $category_excluded_id)->count();

            if($total_assets == 0){
                $step = 'step10';
            }
        }


        if($step == 'step12') {
            //Checking Assets for Select Discount Rate
            $own_assets = \App\LeaseAssets::query()->where('lease_id', '=', $lease_id)
                ->where('specific_use', 1)
                ->whereNotIn('category_id', [8, 5])
                ->whereHas('leaseSelectLowValue', function ($query) {
                    $query->where('is_classify_under_low_value', '=', 'no');
                })
                ->whereHas('leaseDurationClassified', function ($query) {
                    $query->where('lease_contract_duration_id', '=', '3');
                })->count();


            $sublease_assets = \App\LeaseAssets::query()->where('lease_id', '=', $lease_id)
                ->where('specific_use', 2)
                ->whereNotIn('category_id', [8, 5])
                ->whereHas('leaseSelectLowValue', function ($query) {
                    $query->where('is_classify_under_low_value', '=', 'no');
                })
                ->whereHas('leaseDurationClassified', function ($query) {
                    $query->where('lease_contract_duration_id', '=', '3');
                })->count();

            $total_assets = $own_assets + $sublease_assets;


            if($total_assets == 0){
                $step = 'step11';
                $category_excluded = \App\CategoriesLeaseAssetExcluded::query()->get();
                $category_excluded_id = $category_excluded->pluck('category_id')->toArray();

                $total_assets = \App\LeaseAssets::query()->where('lease_id', '=', $lease_id)->whereNotIn('specific_use', [2])
                    ->whereHas('leaseDurationClassified', function ($query) {
                        $query->whereNotIn('lease_contract_duration_id', [1, 2]);
                    })->whereNotIn('category_id', $category_excluded_id)->count();

                if($total_assets == 0){
                    $step = 'step10';
                }
            }
        }

        if($step == 'step13'){
            //Checking Assets for Laese Balence on Dec
            $total_assets = \App\LeaseAssets::query()->where('lease_id', '=', $lease_id)->where('lease_start_date', '<', '2019-01-01')->count();
            if($total_assets == 0){
                $step = 'step12';
                //Checking Assets for Select Discount Rate
                $own_assets = \App\LeaseAssets::query()->where('lease_id', '=', $lease_id)
                    ->where('specific_use', 1)
                    ->whereNotIn('category_id', [8, 5])
                    ->whereHas('leaseSelectLowValue', function ($query) {
                        $query->where('is_classify_under_low_value', '=', 'no');
                    })
                    ->whereHas('leaseDurationClassified', function ($query) {
                        $query->where('lease_contract_duration_id', '=', '3');
                    })->count();


                $sublease_assets = \App\LeaseAssets::query()->where('lease_id', '=', $lease_id)
                    ->where('specific_use', 2)
                    ->whereNotIn('category_id', [8, 5])
                    ->whereHas('leaseSelectLowValue', function ($query) {
                        $query->where('is_classify_under_low_value', '=', 'no');
                    })
                    ->whereHas('leaseDurationClassified', function ($query) {
                        $query->where('lease_contract_duration_id', '=', '3');
                    })->count();

                $total_assets = $own_assets + $sublease_assets;


                if($total_assets == 0){
                    $step = 'step11';
                    $category_excluded = \App\CategoriesLeaseAssetExcluded::query()->get();
                    $category_excluded_id = $category_excluded->pluck('category_id')->toArray();

                    $total_assets = \App\LeaseAssets::query()->where('lease_id', '=', $lease_id)->whereNotIn('specific_use', [2])
                        ->whereHas('leaseDurationClassified', function ($query) {
                            $query->whereNotIn('lease_contract_duration_id', [1, 2]);
                        })->whereNotIn('category_id', $category_excluded_id)->count();

                    if($total_assets == 0){
                        $step = 'step10';
                    }
                }
            }
        }

        if($step == 'step14'){
            $total_assets = \App\LeaseAssets::query()->where('lease_id', '=', $lease_id)
                ->where('lease_start_date', '>=', '2019-01-01')->count();
            if($total_assets == 0){
                $step = 'step13';
            }
        }


        \Log::info('Checking ----- '. $step. ' On URL ------- '. $request->route()->getName());

        if(!$this->verifyStep($step, $lease_id)){
            abort(403);
        }

        return $next($request);
    }

    /**
     * verifies whether a step have been completed or not
     * @param $step
     * @param $lease_id
     * @return bool
     */
    private function verifyStep($step, $lease_id){
        $confrim_steps = \App\LeaseCompletedSteps::query()->where('lease_id', '=', $lease_id)->where('completed_step', '=', $step)->count();
        return $confrim_steps > 0;
    }
}
