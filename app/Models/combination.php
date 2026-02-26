<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Combination extends Model
{
    protected $fillable = ['name', 'slug'];

   protected static function boot()
{
    parent::boot();
    static::saving(function ($combination) {
        $combination->slug = Str::slug($combination->name);
    });
}

    /**
     * Relationship: A combination (PCM) has many subjects.
     * We use withPivot('type') so we can see if it's Principal or Subsidiary.
     */
    public function subjects()
    {
        return $this->belongsToMany(Subject::class)
                    ->withPivot('type')
                    ->withTimestamps();
    }

    /**
     * Custom Accessor: Get only the 3 Principal subjects
     */
    public function getPrincipalsAttribute()
    {
        return $this->subjects()->wherePivot('type', 'principal')->get();
    }

    /**
     * Custom Accessor: Get the Subsidiary subjects (GS, BAM)
     */
    public function getSubsidiariesAttribute()
    {
        return $this->subjects()->wherePivot('type', 'subsidiary')->get();
    }

    /**
 * Relationship: A combination is used by many classes.
 */
public function schoolClasses()
{
    return $this->hasMany(SchoolClass::class, 'combination_id');
}
}