<?php

namespace App\Models\Concerns;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait Auditable
{
    public static function bootAuditable(): void
    {
        static::created(fn (Model $model) => self::writeAuditLog($model, 'created', $model->getAttributes()));

        static::updated(function (Model $model) {
            $changes = $model->getChanges();
            unset($changes['updated_at']);
            if (empty($changes)) {
                return;
            }
            self::writeAuditLog($model, 'updated', $changes);
        });

        static::deleted(fn (Model $model) => self::writeAuditLog($model, 'deleted', null));
    }

    private static function writeAuditLog(Model $model, string $action, ?array $changes): void
    {
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'model_type' => class_basename($model),
            'model_id' => $model->getKey(),
            'description' => class_basename($model).' #'.$model->getKey().' '.$action,
            'changes' => $changes,
        ]);
    }
}
