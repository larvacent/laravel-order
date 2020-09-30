<?php
/**
 * @copyright Copyright (c) 2018 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larvacent.com/
 * @license http://www.larvacent.com/license/
 */

namespace Larva\Order\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;
use Larva\Order\Models\Order;

/**
 * 订单付款成功
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class OrderPaySucceeded extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The user.
     *
     * @var User
     */
    public $user;

    /**
     * @var Order
     */
    public $order;

    /**
     * Create a new notification instance.
     *
     * @param $user
     * @param Order $order
     */
    public function __construct($user, Order $order)
    {
        $this->user = $user;
        $this->order = $order;
    }

    /**
     * Get the notification's channels.
     *
     * @param mixed $notifiable
     * @return array|string
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(Lang::get('Order recharge succeeded'))
            ->line(Lang::get('Your recharge integral is :integral', ['integral' => $this->order->transaction->integral]))
            ->line(Lang::get('Thank you for choosing, we will be happy to help you in the process of your subsequent use of the service.'));
    }
}