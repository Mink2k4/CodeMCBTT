<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteData extends Model
{
    use HasFactory;

    protected $table = 'site_data';

    protected $fillable = [
        'namesite',
        'logo',
        'logo_mini',
        'favicon',
        'title',
        'description',
        'keyword',
        'image_seo',
        'notice',
        'collaborator',
        'agency',
        'distributor',
        'code_tranfer',
        'facebook',
        'zalo',
        'youtube',
        'telegram',
        'phone',
        'email',
        'min_recharge',
        'card_discount',
        'recharge_promotion',
        'show_promotion',
        'start_promotion',
        'end_promotion',
        'partner_id',
        'partner_key',
        'mail_host',
        'mail_port',
        'mail_username',
        'mail_password',
        'google_client_id',
        'google_client_secret',
        'google_redirect',
        'facebook_client_id',
        'facebook_client_secret',
        'facebook_redirect',
        'balance_telegram',
        'telegram_bot',
        'telegram_token',
        'telegram_chat_id',
        'telegram_callback',
        'script_header',
        'script_footer',
        'is_admin',
        'token_web',
        'username_web',
        'status',
        'domain',
    ];
}
