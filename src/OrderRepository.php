<?php
/**
 * @copyright Copyright (c) 2018 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larvacent.com/
 * @license http://www.larvacent.com/license/
 */

namespace Larva\Order;

use Larva\Order\Models\Order;

/**
 * Class OrderRepository
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class OrderRepository
{

    /**
     * 获取订单
     * @param string $id
     * @return Order|null
     */
    public function findOrder($id)
    {
        return Order::where('id', $id)->first();
    }

    /**
     * 获取订单付款参数
     * @param string $orderId 订单号
     * @param string $channel 付款渠道
     * @param string $type 付款方式
     * @return false|array  失败返回false,成功返回付款凭证
     */
    public function charge($orderId, $channel = 'alipay', $type = 'scan')
    {
        $order = static::findOrder($orderId);
        if ($order) {
            $order->charge()->create([
                'user_id' => $order->user_id,
                'amount' => $order->amount,
                'channel' => $channel ? $channel : $order->payment_channel,
                'subject' => trans('order::order.payment_order') . $order->id,
                'body' => $order->product->getName(),
                'client_ip' => $order->client_ip,
                'type' => $type ? $type : $order->payment_type,//交易类型
            ]);
            return $order->charge->credential;
        } else {
            return false;
        }
    }

}
