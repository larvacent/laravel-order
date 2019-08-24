<?php
/**
 * @copyright Copyright (c) 2018 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larvacent.com/
 * @license http://www.larvacent.com/license/
 */

namespace Larva\Order\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Larva\Order\Models\Order;
use Larva\Transaction\Events\ChargeShipped;

/**
 * Class ChargeShippedListener
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class ChargeShippedListener implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param ChargeShipped $event
     * @return void
     */
    public function handle(ChargeShipped $event)
    {
        if ($event->charge->order instanceof Order) {//订单支付成功
            $event->charge->order->setSucceeded();
        }
    }
}