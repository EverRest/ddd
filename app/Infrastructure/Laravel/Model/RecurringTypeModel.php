<?php

declare(strict_types=1);

namespace App\Infrastructure\Laravel\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RecurringTypeModel extends Model
{
    use HasFactory;

    /**
     * @var bool $timestamps
     */
    public $timestamps = false;

    /**
     * @var string $table
     */
    protected $table = 'recurring_types';

    /**
     * @var string[] $fillable
     */
    protected $fillable = [
        'recurring_type',
    ];

    /**
     * @return HasMany
     */
    public function recurringPatterns(): HasMany
    {
        return $this->hasMany(RecurringPatternModel::class);
    }
}
