<?php

declare(strict_types=1);

namespace App\Infrastructure\Laravel\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class EventModel extends Model
{
    use HasFactory;

    /**
     * @var bool $timestamps
     */
    public $timestamps = false;

    /**
     * @var string $table
     */
    protected $table = 'events';

    /**
     * @var string[] $fillable
     */
    protected $fillable = [
        'parent_id',
        'recurring_pattern_id',
        'title',
        'description',
        'start',
        'end',
    ];

    /**
     * @var string[] $casts
     */
    protected $casts = [
        'start' => 'timestamp',
        'end' => 'timestamp',
    ];

    /**
     * @return BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(EventModel::class, 'parent_id');
    }

    /**
     * @return HasMany
     */
    public function children(): HasMany
    {
        return $this->hasMany(EventModel::class, 'parent_id');
    }

    /**
     * @return HasOne
     */
    public function recurringPattern(): HasOne
    {
        return $this->hasOne(RecurringPatternModel::class, 'id', 'recurring_pattern_id');
    }
}
