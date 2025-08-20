<?php

namespace admin\quizzes\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Support\Facades\Config;

class QuizAnswer extends Model
{
    use HasFactory, Sortable;

    protected $fillable = [
        'question_id',
        'answer_text',
        'is_correct',
    ];

    protected $sortable = [
        'answer_text',
        'is_correct',
        'created_at',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
    ];

    public function question()
    {
        return $this->belongsTo(QuizQuestion::class, 'question_id');
    }

    public function scopeFilter($query, $answer_text)
    {
        if ($answer_text) {
            return $query->where('answer_text', 'like', '%' . $answer_text . '%');
        }
        return $query;
    }

    public function scopeFilterByStatus($query, $status)
    {
        if (!is_null($status)) {
            return $query->where('is_correct', $status);
        }
        return $query;
    }

    public static function getPerPageLimit(): int
    {
        return Config::has('get.admin_page_limit')
            ? Config::get('get.admin_page_limit')
            : 10;
    }
}
