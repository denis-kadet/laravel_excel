<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $guarded = false;
    protected $table = 'tasks';

    const STATUS_PROCESS = 1;
    const STATUS_SUCCESS = 2;
    const STATUS_ERROR = 3;

    public static function getStatuses()
    {
        return [
            self::STATUS_PROCESS => 'Обработка',
            self::STATUS_SUCCESS => 'Успешно',
            self::STATUS_ERROR => 'Ошибка',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function file()
    {
        return $this->belongsTo(File::class, 'file_id', 'id');
    }
}
