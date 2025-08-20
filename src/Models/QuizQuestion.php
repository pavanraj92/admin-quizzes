<?php

namespace admin\quizzes\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Support\Facades\Config;

class QuizQuestion extends Model
{
    use HasFactory, Sortable;

    protected $fillable = [
        'quiz_id',
        'question_text',
        'question_type',
        'explanation',
        'points',
    ];

    protected $sortable = [
        'question_text',
        'question_type',
        'points',
        'created_at',
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function answers()
    {
        return $this->hasMany(QuizAnswer::class, 'question_id');
    }

    public function scopeFilter($query, $question_text)
    {
        if ($question_text) {
            return $query->where('question_text', 'like', '%' . $question_text . '%');
        }
        return $query;
    }

    public function scopeFilterByStatus($query, $status)
    {
        if (!is_null($status)) {
            return $query->where('question_type', $status);
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
