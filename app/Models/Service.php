<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Service extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'services';
    protected $fillable = [
        'name',
        'slug',
        'image',
        'short_description',
        'html_content',
        'status',
        'meta_title',
        'meta_description',
    ];
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($service) {
            if ($service->id == self::defaultService()->id) {
                throw new \Exception("Không thể xoá dịch vụ này.");
            }
            $defaultService = self::defaultService();
            // Chuyển đặt lịch thuộc dịch vụ sang dịch vụ mặc định
            $service->contacts()->update(['service_id' => $defaultService->id]);
        });
    }
    public function contacts()
    {
        return $this->hasMany(Contact::class, 'service_id', 'id');
    }
    public static function defaultService()
    {
        return self::firstOrCreate(['slug' => 'uncategorized'], [
            'name' => 'Uncategorized',
            'slug' => 'uncategorized',
            'image' => 'public/assets/clients/uploads/13-09-2024/abf94830_nSTKZ6jEZm.webp',
            'status' => 0,
            'short_description' => 'uncategorized',
            'html_content' => 'uncategorized',
        ]);
    }
}
