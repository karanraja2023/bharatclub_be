<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JoinClubsMember extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tbl_join_clubsmember';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'user_id', 'clubs_id', 'primary_member_name', 'company_name', 'mobile_no', 'member_ic_passport_no', 'email_address', 'residence_address_local','spouse_name','spouse_mobile_no','spouse_email','spouse_ic_passport_no','spouse_children_name_age','payment_toal_amount','payment_bankname','payment_date','payment_reference_number','payment_receipt_no','payment_receipt_url','status', 'created_by', 'modified_by'];
}
