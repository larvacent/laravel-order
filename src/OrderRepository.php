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
     * 获取充值订单
     * @param string $id
     * @return Order|null
     */
    public function findOrder($id)
    {
        return Order::where('id', $id)->first();
    }

}
