<?php namespace App\Jobs;

use App\Models\Configuration;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Osiset\ShopifyApp\Actions\CancelCurrentPlan;
use Osiset\ShopifyApp\Objects\Values\ShopDomain;
use Osiset\ShopifyApp\Storage\Commands\Shop as IShopCommand;
use Osiset\ShopifyApp\Storage\Queries\Shop as IShopQuery;
use stdClass;

class AppUninstalledJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Shop's myshopify domain
     *
     * @var ShopDomain|string
     */
    public $shopDomain;

    /**
     * The webhook data
     *
     * @var object
     */
    public $data;

    /**
     * Create a new job instance.
     *
     * @param string   $shopDomain The shop's myshopify domain.
     * @param stdClass $data       The webhook data (JSON decoded).
     *
     * @return void
     */
    public function __construct($shopDomain, $data)
    {
        $this->shopDomain = $shopDomain;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle( IShopCommand $shopCommand , IShopQuery $shopQuery, CancelCurrentPlan $cancelCurrentPlanAction  ) : bool
    {

        // info( json_encode($this->data) );
        info( $this->data->domain );

        // Convert domain
        // $this->shopDomain = ShopDomain::fromNative($this->shopDomain);
        // info( $this->shopDomain->toNative() );

        $shop__domain = $this->data->domain;
        // info( $shop__domain );
        $shop__ = User::where('name', $shop__domain)->get()->toArray();
        // info( $shop__[0] );
        // info( $shop__[0]['id'] );
        if( $shop__ ){
            if( isset($shop__[0]) ){
                $shopId = $shop__[0]['id'];
                ///////////////////////////////////////////////////
                // $charge_selected = Charge::where( [ 'user_id' => $shopId , 'status' => "ACTIVE"] )->first();
                // info($charge_selected);
                // $charge_selected->cancelled_on = date('Y-m-d 00:00:00');
                // $charge_selected->expires_on = date('Y-m-d 00:00:00');
                // $charge_selected->status = 'CANCELLED';
                // $charge_selected->save();
                // ///////////////////////////////////////////////////
                // DB::table('charges')->where([ 'user_id' => $shopId , 'status' => "ACTIVE"] )->update(
                //     array(
                //         'cancelled_on' => date('Y-m-d 00:00:00'),
                //         'expires_on' => date('Y-m-d 00:00:00'),
                //         'status' => "CANCELLED"
                //     )
                // );
                ///////////////////////////////////////////////////
                // $shop->scriptTag_id = null;
                // $shop->status = false; if present or used in db
                ///////////////////////////////////////////////////
                $shop = User::find($shopId);
                $shop->shopify_freemium = 0;
                $shop->plan_id = NULL;
                $shop->save();
                $config = Configuration::where('shop_url', $this->data->domain)->first();
                $config->webhook_status = 0;
                $config->status = 0;
                $config->shopify_scripttag_id = NULL;
                $config->save();
                ///////////////////////////////////////////////////
                $shop = User::find($shopId);
                $shop->delete();
                ///////////////////////////////////////////////////

                // $shop = $shopQuery->getByDomain($this->shopDomain);
                // $shopId = $shop->getId();
                // $cancelCurrentPlanAction($shopId);
                // $shopCommand->clean($shopId);
                // $shopCommand->softDelete($shopId);
            }
        }
        info( true );
        return true;
    }
}
