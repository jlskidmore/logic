<?php

namespace App\Models;

use App\Enums\Core\BillFrequency;
use App\Traits\HasLogTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property float     $allowed_overage
 * @property int       $allowed_qty
 * @property mixed     $addons
 * @property mixed     $account
 * @property mixed     $item
 * @property mixed     $meta
 * @property mixed     $qty
 * @property mixed     $quote
 * @property mixed     $price
 */
class AccountItem extends Model
{
    use HasLogTrait;
    
    protected $guarded = ['id'];
    public    $dates   = ['next_bill_date', 'suspend_on', 'terminate_on', 'requested_termination_date'];
    public    $casts   = [
        'frequency'    => BillFrequency::class,
        'meta'         => 'json'
    ];

    public array $tracked = [
        'allowed_overage' => "Allowed Overage",
        'allowed_qty'     => "Allowed Quantity",
        'addons'          => "Addons",
        'account'         => "Account",
        'item'            => "Item",
        'meta'            => "Meta",
        'qty'             => "Quantity",
        'quote'           => "Quote",
        'price'           => "Price",
    ];

    /**
     * Pivot to Account
     * @return BelongsTo
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * Pivot to Bill Item
     * @return BelongsTo
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(BillItem::class, 'bill_item_id');
    }

    /**
     * A service item that was sold via a quote.
     * @return BelongsTo
     */
    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class);
    }

    /**
     * Account items can have addons.
     * @return HasMany
     */
    public function addons(): HasMany
    {
        return $this->hasMany(AccountAddon::class, 'account_bill_item_id');
    }

    /**
     * Get the total cost of any addons.
     * @return float
     */
    public function getAddonTotalAttribute(): float
    {
        $total = 0;
        foreach ($this->addons as $addon)
        {
            $total += $addon->price * $addon->qty;
        }
        return round($total, 2);
    }

    /**
     * Get code from billable item
     * @return string
     */
    public function getCodeAttribute(): string
    {
        return $this->item->code;
    }

    /**
     * Get name from billable item.
     * @return string
     */
    public function getNameAttribute(): string
    {
        return $this->item->name;
    }

    /**
     * If item is contracted, then what is the payoff amount. We
     * will need to take setting:account.term_payoff percentage
     * and apply it to the number of months remaining.
     * @return float
     */
    public function getPayoffAmountAttribute(): float
    {
        if (!$this->quote) return 0; // No contracted quote
        if ($this->quote->term <= 0) return 0; // Contract has no term. MTM.
        $endOfContract = $this->quote->contract_expires;
        $monthsBetween = now()->diffInMonths($endOfContract);
        $totalGross = ($this->price * $this->qty) * $monthsBetween;
        $percOwed = (int) setting('account.term_payoff') / 100; // 80 = .8
        return $totalGross * $percOwed;
    }



    /**
     * Send Immediate Termination
     * @return void
     */
    public function sendImmediateSuspension(): void
    {
        template('account.suspendImmediate', $this->account->admin, [$this]);
    }

    /**
     * Send immediate termination notice.
     * @return void
     */
    public function sendImmediateTermination(): void
    {
        template('account.terminateImmediate', $this->account->admin, [$this]);
    }


    /**
     * Get metadata answer for a requirement.
     * @param BillItemMeta $meta
     * @param int|null     $qtyIndex
     * @return string|null
     */
    public function getMetaFor(BillItemMeta $meta, ?int $qtyIndex = null): ?string
    {
        $existing = $this->meta;
        if ($qtyIndex == null)
        {
            if (!is_array($existing) || !array_key_exists($meta->id, $existing)) return null;
            return $existing[$meta->id];
        }
        else
        {
            if (!is_array($existing) || !array_key_exists($meta->id . "_" . $qtyIndex, $existing)) return null;
            return $existing[$meta->id . "_" . $qtyIndex];
        }
    }

    /**
     * Iterate metadata for item.
     * @param bool $onlyCustomer
     * @return string|null
     */
    public function iterateMeta(bool $onlyCustomer = false): ?string
    {
        if (!$this->item->meta()->count()) return null;
        $data = null;
        foreach ($this->item->meta as $meta)
        {
            if (!$meta->customer_viewable && $onlyCustomer) continue;
            if ($meta->per_qty)
            {
                foreach (range(1, $this->qty) as $idx)
                {
                    $ans = $this->getMetaFor($meta, $idx);
                    if (!$ans) continue;
                    $data .= "<small><b>$meta->item</b>: " . $ans . "</small><br/>";
                }
            }
            else
            {
                if (!$this->getMetaFor($meta)) continue;
                $data .= "<small><b>$meta->item</b>: " . $this->getMetaFor($meta) . "</small><br/>";
            }
        }
        return $data;
    }

    /**
     * Update Metadata for an Item
     * @param int         $int
     * @param string|null $val
     * @param int|null    $qtyIdx
     * @return void
     */
    public function updateMeta(int $int, ?string $val = null, ?int $qtyIdx = null): void
    {
        $meta = $this->meta ?: [];
        if ($qtyIdx == null)
        {
            $meta[$int] = $val;
        }
        else
        {
            $meta[$int . "_" . $qtyIdx] = $val;
        }
        $this->update(['meta' => $meta]);
    }

}
