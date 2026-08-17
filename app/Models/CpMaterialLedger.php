<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CpMaterialLedger extends Model
{
    protected $table = 'cp_material_ledger';

    protected $fillable = [
        'cp_id', 'material_name', 'quantity', 'unit', 'rate',
        'total_amount', 'entry_date', 'invoice_number', 'invoice_file',
        'added_by', 'remarks', 'source',
    ];

    public function channelPartner()
    {
        return $this->belongsTo(ChannelPartner::class, 'cp_id');
    }

    public function addedByUser()
    {
        return $this->belongsTo(User::class, 'added_by');
    }
}
