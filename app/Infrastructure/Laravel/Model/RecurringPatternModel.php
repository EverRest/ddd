<?php

declare(strict_types=1);

namespace App\Infrastructure\Laravel\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecurringPatternModel extends Model
{
    use HasFactory;

    /**
     * @var bool $timestamps
     */
    public $timestamps = false;

    /**
     * @var string $table
     */
    protected $table = 'recurring_patterns';

    /**
     * @var string[] $fillable
     */
    protected $fillable = [
        'event_id',
        'recurring_type_id',
        'repeat_until',
        'separation_count',
        'max_num_of_occurrences',
        'day_of_month',
        'day_of_week',
        'week_of_month',
        'month_of_year',
    ];

    /**
     * @var string[] $casts
     */
    protected $casts = [
        'repeat_until' => 'timestamp',
    ];

    /**
     * @return BelongsTo
     */
    public function events(): BelongsTo
    {
        return $this->belongsTo(EventModel::class, 'event_id');
    }

    /**
     * @return BelongsTo
     */
    public function recurringType(): BelongsTo
    {
        return $this->belongsTo(RecurringTypeModel::class, 'recurring_type_id');
    }
}
