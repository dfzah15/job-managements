<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait LogsActivity
{
    protected function logActivity($aksi, $modul, $deskripsi = null, $dataOld = null, $dataNew = null)
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'aksi' => $aksi,
            'modul' => $modul,
            'deskripsi' => $deskripsi,
            'data_old' => $dataOld,
            'data_new' => $dataNew,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }

    // Model events untuk auto logging
    public static function bootLogsActivity()
    {
        static::created(function ($model) {
            if (method_exists($model, 'logActivity')) {
                $model->logActivity(
                    'create',
                    class_basename($model),
                    'Data ' . class_basename($model) . ' baru ditambahkan',
                    null,
                    $model->toArray()
                );
            }
        });

        static::updated(function ($model) {
            if (method_exists($model, 'logActivity')) {
                $model->logActivity(
                    'update',
                    class_basename($model),
                    'Data ' . class_basename($model) . ' diupdate',
                    $model->getOriginal(),
                    $model->getChanges()
                );
            }
        });

        static::deleted(function ($model) {
            if (method_exists($model, 'logActivity')) {
                $model->logActivity(
                    'delete',
                    class_basename($model),
                    'Data ' . class_basename($model) . ' dihapus',
                    $model->toArray(),
                    null
                );
            }
        });
    }
}