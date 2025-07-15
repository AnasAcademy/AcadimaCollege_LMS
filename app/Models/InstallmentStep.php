<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class InstallmentStep extends Model implements TranslatableContract
{
    use Translatable;

    protected $table = 'installment_steps';
    public $timestamps = false;
    protected $guarded = ['id'];

    public $translatedAttributes = ['title'];

    public function getTitleAttribute()
    {
        return getTranslateAttributeValue($this, 'title');
    }

    public function getAmountAttribute()
    {
        return $this->attributes['amount'] + 0;
    }


    /*********
     * Relations
     * */
    public function installment()
    {
        return $this->belongsTo(Installment::class, 'installment_id', 'id');
    }

    /*********
     * Helpers
     * */

    public function getPrice($itemPrice = 1)
    {
        if ($this->amount_type == 'percent') {
            return ($itemPrice * $this->amount) / 100;
        } else {
            return $this->amount;
        }
    }

    
     public function getDeadlineTitle($itemPrice = 1, $itemId = null, $index = null, $totalCount = null)
    {
        $percentText = ($this->amount_type == 'percent') ? "({$this->amount}%)" : '';

        // Generate ordinal order (e.g. 1st, 2nd, Final)
        $orderText = $this->title;
        if (!is_null($index) && !is_null($totalCount)) {
            if ($index + 1 === $totalCount) {
                $orderText = 'Final Installment';
            } else {
                
                $orderText = $this->ordinal($index + 1) . ' Installment';
               
            }
        }

        if (!empty($itemId)) {
            $bundle = Bundle::where('id', $itemId)->first();

            $formattedDate = dateTimeFormat(
                ($this->installment->deadline_type == 'days')
                    ? (($this->deadline * 86400) + $bundle->start_date)
                    : $this->deadline,
                'j M Y'
            );

            return trans('update.amount_after_n_days', [
                'amount' => handlePrice($this->getPrice($itemPrice)),
                'title' => $orderText,
                'days' => $formattedDate
            ]);
        }

        return trans('update.amount_after_n_days', [
            'amount' => handlePrice($this->getPrice($itemPrice)),
            'title' => $orderText,
            'days' => $this->deadline
        ]);
    }

    // ✅ Make it a private method inside the class
    private function ordinal($number)
    {
        $suffix = 'th';
        if (!in_array($number % 100, [11, 12, 13])) {
            switch ($number % 10) {
                case 1: $suffix = 'st'; break;
                case 2: $suffix = 'nd'; break;
                case 3: $suffix = 'rd'; break;
            }
        }
        return $number . $suffix;
    }
}
