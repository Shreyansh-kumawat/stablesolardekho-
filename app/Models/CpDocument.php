<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CpDocument extends Model
{
    protected $fillable = [
        'cp_id', 'title', 'document_type', 'file_path', 'file_name',
        'file_size', 'uploaded_by', 'remarks',
    ];

    public function channelPartner()
    {
        return $this->belongsTo(ChannelPartner::class, 'cp_id');
    }

    public function uploadedByUser()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
