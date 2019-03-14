<?php

use Illuminate\Database\Seeder;
class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

    	Eloquent::unguard();

    	// Truncate all tables, except migrations
        $tables = DB::select('SHOW TABLES');
        foreach ($tables as $table) {
            if ($table->{'Tables_in_'.env('DB_DATABASE')} !== 'migrations'){
				DB::statement('SET FOREIGN_KEY_CHECKS=0;');
				DB::table($table->{'Tables_in_'.env('DB_DATABASE')})->truncate();
				DB::statement('SET FOREIGN_KEY_CHECKS=1;');
				$this->command->info($table->{'Tables_in_'.env('DB_DATABASE')}.' table truncated!');
            }
		}

    	//import currency and countries tables
		$path = 'database/sqls/countries.sql';
        DB::unprepared(file_get_contents($path));
        $this->command->info('Country table seeded!');

        $path = 'database/sqls/currencies.sql';
        DB::unprepared(file_get_contents($path));
        $this->command->info('Currency table seeded!');

        $path = 'database/sqls/email_templates.sql';
        DB::unprepared(file_get_contents($path));
        $this->command->info('Email Templates table seeded!');

        $path = 'database/sqls/states.sql';
        DB::unprepared(file_get_contents($path));
        $this->command->info('States table seeded!');

		$this->call(AdminUsers::class);
		$this->call(ContractClassifications::class);
		$this->call(ContractEscalationBasis::class);
		$this->call(DepreciationMethods::class);
        $this->call(InitialValuationModels::class);
		$this->call(EmailTemplates::class);
		$this->call(EscalationAmountCalculatedOn::class);
		$this->call(IndustryTypeSeeder::class);
		$this->call(LeaseAccountingTreatment::class);
        $this->call(EscalationFrequency::class);
		$this->call(LeaseAssetCategories::class);
		$this->call(LeaseAssetPaymentsNature::class);
		$this->call(LeaseContractDuration::class);
		$this->call(LeasePaymentsComponents::class);
		$this->call(LeasePaymentsEscalationClause::class);
		$this->call(LeasePaymentsFrequency::class);
		$this->call(LeasePaymentsInterval::class);
		$this->call(LeasesExcludedFromTransitionalValuation::class);
		$this->call(PermissionSeeder::class);
		$this->call(RateTypes::class);
        $this->call(ReportingPeriods::class);
		$this->call(UseOfLeaseAsset::class);

        $path = 'database/sqls/subscription_plans.sql';
        DB::unprepared(file_get_contents($path));
        $this->command->info('Subscription table seeded!');

    }
}
