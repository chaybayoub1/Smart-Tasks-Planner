<?php
// app/Models/Note.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Note extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','subject_id','title','content','is_pinned'];

    protected $casts = ['is_pinned' => 'boolean'];

    public function user()    { return $this->belongsTo(User::class); }
    public function subject() { return $this->belongsTo(Subject::class); }

    public function excerpt(int $chars = 120): string
    {
        return strlen($this->content) > $chars
            ? substr(strip_tags($this->content), 0, $chars) . '…'
            : strip_tags($this->content);
    }
}
