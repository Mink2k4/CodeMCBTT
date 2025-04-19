<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Affiliate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'visits',
        'registrations',
        'referrals',
        'conversion_rate',
        'total_earnings',
        'available_earnings',
    ];

    /**
     * Cập nhật tỷ lệ chuyển đổi (Conversion Rate)
     */
    public function updateConversionRate()
    {
        if ($this->visits > 0) {
            $this->conversion_rate = ($this->referrals / $this->visits) * 100;
        } else {
            $this->conversion_rate = 0;
        }
        $this->save();
    }

    /**
     * Tăng lượt truy cập (visits)
     */
    public function incrementVisit()
    {
        $this->increment('visits');
    }
}
