<?php
/**
 * @copyright Copyright (c) 2018 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larvacent.com/
 * @license http://www.larvacent.com/license/
 */

namespace Larva\Order;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Larva\Order\Listeners\ChargeClosedListener;
use Larva\Order\Listeners\ChargeFailureListener;
use Larva\Order\Listeners\ChargeShippedListener;
use Larva\Transaction\Events\ChargeClosed;
use Larva\Transaction\Events\ChargeFailure;
use Larva\Transaction\Events\ChargeShipped;
use Larva\Transaction\Events\TransferFailure;
use Larva\Transaction\Events\TransferShipped;

/**
 * Class IntegralServiceProvider
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class OrderServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //$this->loadViewsFrom(__DIR__.'/../resources/views', 'order');
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../database/migrations' => database_path('migrations'),
            ], 'order');

            $this->publishes([
                __DIR__ . '/../translations' => resource_path('lang/vendor/order'),
            ], 'order');

            $this->publishes([
                dirname(__DIR__) . '/config/order.php' => config_path('order.php'),],
                'order'
            );
        }

        $this->loadTranslationsFrom(__DIR__ . '/../translations', 'order');

        // Transaction
        Event::listen(ChargeClosed::class, ChargeClosedListener::class);//支付关闭
        Event::listen(ChargeFailure::class, ChargeFailureListener::class);//支付失败
        Event::listen(ChargeShipped::class, ChargeShippedListener::class);//支付成功
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {

    }

}
