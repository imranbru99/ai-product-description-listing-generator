<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'workspace_id',
        'group',
        'key',
        'value',
        'is_encrypted',
    ];

    protected function casts(): array
    {
        return [
            'is_encrypted' => 'boolean',
        ];
    }

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }

    /**
     * Get value with automatic decryption if encrypted.
     */
    public function getValueAttribute(?string $val): ?string
    {
        if ($this->is_encrypted && $val) {
            try {
                return decrypt($val);
            } catch (\Exception) {
                return $val;
            }
        }
        return $val;
    }

    /**
     * Set value with automatic encryption if requested.
     */
    public function setValueAttribute(?string $val): void
    {
        if ($this->is_encrypted && $val) {
            $this->attributes['value'] = encrypt($val);
        } else {
            $this->attributes['value'] = $val;
        }
    }

    /**
     * Static helper to get setting value.
     */
    public static function get(string $key, mixed $default = null, ?int $workspaceId = null): mixed
    {
        $setting = static::where('workspace_id', $workspaceId)
            ->where('key', $key)
            ->first();

        return $setting ? $setting->value : $default;
    }

    /**
     * Static helper to set setting value.
     */
    public static function set(string $key, mixed $value, string $group = 'general', ?int $workspaceId = null, bool $encrypted = false): self
    {
        return static::updateOrCreate(
            ['workspace_id' => $workspaceId, 'group' => $group, 'key' => $key],
            ['value' => $value, 'is_encrypted' => $encrypted]
        );
    }
}
