<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Osiset\ShopifyApp\Storage\Models\Plan;

class PlanSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);

        /*
         # Create a recurring "Demo" plan for $5.00, with 7 trial days, which will be presented on install to the shop and have the ability to issue usage charges to a maximum of $10.00
            INSERT INTO plans (
                `type`,
                `name`,
                `price`,
                `interval`,
                `capped_amount`,
                `terms`,
                `trial_days`,
                `test`,
                `on_install`,
                `created_at`,
                `updated_at`) VALUES
            ('RECURRING/ONETIME','Test Plan',5.00,'EVERY_30_DAYS',10.00,'Test terms',7,FALSE,1,NULL,NULL);
        */

        try {
            $Plan = new Plan();
            $Plan->type = "RECURRING";
            $Plan->name = "Basic Plan";
            $Plan->price = 4.99;
            $Plan->interval = "EVERY_30_DAYS";
            $Plan->capped_amount = 10.00;
            $Plan->terms = "Basic Plan ~ amount 4.99";
            $Plan->trial_days = 7;
            $Plan->test = 1;
            $Plan->on_install = 1;
            info($Plan->save());

            $Plan = new Plan();
            $Plan->type = "RECURRING";
            $Plan->name = "Premiere Plan ~ amount 9.99";
            $Plan->price = 9.99;
            $Plan->interval = "EVERY_30_DAYS";
            $Plan->capped_amount = 10.00;
            $Plan->terms = "Premiere Plan ~ amount 9.99";
            $Plan->trial_days = 14;
            $Plan->test = 1;
            $Plan->on_install = 1;
            info($Plan->save());
        }catch (\Exception $e){
            return dd( "\nMESSAGE :: ". $e->getMessage() ."\nCODE :: ". $e->getCode() ."\nLINE :: ". $e->getLine()  );
        }

    }
}
