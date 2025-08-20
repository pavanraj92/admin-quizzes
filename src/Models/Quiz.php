<?php

namespace admin\quizzes\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Config;
use Kyslik\ColumnSortable\Sortable;
use admin\courses\Models\Course;

class Quiz extends Model
{
    use HasFactory, Sortable;

    protected $fillable = [
        'user_id',
        'course_id',
        'title',
        'description',
        'difficulty',
        'passing_score',
        'time_limit',
        'status',
    ];

    public $sortable = [
        'title',
        'status',
        'created_at'
    ];

    public function scopeFilter($query, $keyword)
    {
        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', '%' . $keyword . '%');
            });
        }
        return $query;
    }
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // Relationships
    public function questions()
    {
        return $this->hasMany(QuizQuestion::class);
    }

    public function scopeFilterByStatus($query, $status)
    {
        if (!is_null($status)) {
            return $query->where('status', $status);
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
