<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClaimFrom extends Model
{
    /**
     * Define fillable
     * @var array
     */
    protected $fillable = [
        'company_name','contact_name','address','city','state','zip',
        'phone','fax','email','other_claim','contract_date','contract_date',
        'extra_amount','contact_total','payment','claim_amount','project_state',
        'date_of_completion','project_name','project_address','county_of_property',
        'project_owner','contact_address','contact_city','contact_state','contact_zip',
        'contact_phone','original_contractor','oc_address','oc_city','oc_state','oc_zip',
        'oc_phone','sc_name','sc_address','sc_city','sc_state','sc_zip','sc_phone',
        'contract','project','payment_bond','pb_company','pb_name','pb_address',
        'pb_city','pb_state','pb_zip','pb_phone','mpi_name','mpi_relationship',
        'business_name','mpi_phone','mpi_email','customer_name','customer_address',
        'customer_city','customer_state','customer_zip','customer_phone','customer_no',
        'customer_account','value_of_order','job_no','po_no','contract_no',
        'date_product_needed','start_work_date','payment_terms','billing_cycle',
        'project_status_other','check_list_other','miscellaneous','web_search_other'
    ];
}
