<?php
/**
 * @copyright Copyright (c) 2018 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larvacent.com/
 * @license http://www.larvacent.com/license/
 */

namespace Larva\Order\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Larva\Order\Models\Order;
use Larva\Transaction\Events\ChargeClosed;

/**
 * Class ChargeClosedListener
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class ChargeClosedListener implements ShouldQueue
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
     * @param ChargeClosed $event
     * @return void
     */
    public function handle(ChargeClosed $event)
    {
        if ($event->charge->order instanceof Order) {//订单关闭
            $event->charge->order->setFailure();
        }
    }
}