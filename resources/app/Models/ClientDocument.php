<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientDocument extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'client_id',
        'risk_id',
        'document_type',
        'file_path',
        'original_name',
        'mime_type',
        'file_size',
        'notes',
        'uploaded_by',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function risk()
    {
        return $this->belongsTo(Risk::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}


