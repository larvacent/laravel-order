<?php
/**
 * @copyright Copyright (c) 2018 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larvacent.com/
 * @license http://www.larvacent.com/license/
 */

namespace Larva\Order\Models;

use Illuminate\Database\Eloquent\Model;
use Larva\Order\Contracts\Product;
use Larva\Order\Notifications\OrderPaySucceeded;
use Larva\Transaction\Models\Charge;

/**
 * 订单表
 * @property string $id
 * @property int $user_id
 * @property int $amount
 * @property string $payment_channel 支付渠道
 * @property string $payment_type 支付类型
 * @property string $status
 * @property string $client_ip
 * @property string $created_at
 * @property string $updated_at
 * @property string $succeeded_at
 *
 * @property Charge $charge
 * @property Product $product
 * @property User $user
 * @author Tongle Xu <xutongle@gmail.com>
 */
class Order extends Model
{
    const STATUS_PENDING_PAYMENT = 'Pending Payment';//待付款
    const STATUS_SUCCEEDED_PAYMENT = 'Succeeded Payment';//付款成功
    const STATUS_FAILED_PAYMENT = 'Payment failed';//付款失败
    const STATUS_CLOSE = 'Close';//订单关闭
    const STATUS_PREPARING_GOODS = 'Preparing goods';//正在备货
    const STATUS_SHIPPED = 'Shipped';//已经发货
    const STATUS_SUCCEEDED = 'Succeeded';//交易成功
    const STATUS_RETURN = 'Return';//退货
    const STATUS_RETURNED = 'Returned';//已退货

    /**
     * 与模型关联的数据表。
     *
     * @var string
     */
    protected $table = 'orders';

    /**
     * 可以批量赋值的属性
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'amount', 'quantity', 'channel', 'type', 'status', 'client_ip', 'succeeded_at'
    ];

    /**
     * 应该被调整为日期的属性
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'succeeded_at'
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id = $model->generateId();
            $model->status = static::STATUS_PENDING_PAYMENT;
        });
    }

    /**
     * Get the user that the charge belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(
            config('auth.providers.' . config('auth.guards.web.provider') . '.model')
        );
    }

    /**
     * Get the entity's charge.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function charge()
    {
        return $this->morphOne(Charge::class, 'order');
    }

    /**
     * 多态关联 创建订单的产品
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function product()
    {
        return $this->morphTo();
    }

    /**
     * 生成流水号
     * @return int
     */
    public function generateId()
    {
        $i = rand(0, 9999);
        do {
            if (9999 == $i) {
                $i = 0;
            }
            $i++;
            $id = time() . str_pad($i, 4, '0', STR_PAD_LEFT);
            $row = static::where('id', '=', $id)->exists();
        } while ($row);
        return $id;
    }

    /**
     * 设置订单状态
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->update(['status' => $status]);
    }

    /**
     * 设置付款成功
     */
    public function setSucceeded()
    {
        $this->update(['channel' => $this->charge->channel, 'type' => $this->charge->type, 'status' => static::STATUS_SUCCEEDED_PAYMENT, 'succeeded_at' => $this->freshTimestamp()]);
        $this->user->notify(new OrderPaySucceeded($this->user, $this));
    }

    /**
     * 设置付款失败
     */
    public function setFailure()
    {
        $this->update(['status' => static::STATUS_FAILED_PAYMENT]);
    }
}
