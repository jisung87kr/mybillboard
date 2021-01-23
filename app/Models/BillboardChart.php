<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillboardChart extends Model
{
    use HasFactory;

//    protected $fillable = ['chart_category_id', 'chart_date', 'chart', 'video', 'created_at', 'updated_at'];
    protected $guarded = [];

    public function chartCategory()
    {
        return $this->belongsTo(BillboardChartCategory::class);
    }
}
