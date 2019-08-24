<?php
/**
 * @copyright Copyright (c) 2018 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larvacent.com/
 * @license http://www.larvacent.com/license/
 */

namespace Larva\Order\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Larva\Order\Models\Order;
use Larva\Transaction\Events\ChargeFailure;

/**
 * Class ChargeFailureListener
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class ChargeFailureListener implements ShouldQueue
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
     * @param ChargeFailure $event
     * @return void
     */
    public function handle(ChargeFailure $event)
    {
        if ($event->charge->order instanceof Order) {//订单支付失败
            $event->charge->order->setFailure();
        }
    }
}