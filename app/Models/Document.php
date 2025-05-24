<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Storage;

class Document extends Model
{
    protected $fillable = ['lead_id', 'file_path', 'comments', 'document_category'];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }
    protected static function booted(): void
    {
        self::deleting(function (Document $customerDocument) {
            Storage::disk('public')->delete($customerDocument->file_path);
        });
    }
}
