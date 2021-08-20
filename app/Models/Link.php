<?php

namespace App\Models;

use Illuminate\Http\UploadedFile;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Link extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'value',
        'public'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'favicon_url',
    ];

    /**
     * Get the user of the link
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the team of the link
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function team(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Check if the link is public.
     *
     * @return bool
     */
    public function isPublic(): bool
    {
        return $this->public;
    }

    /**
     * Update the link's favicon.
     *
     * @param  \Illuminate\Http\UploadedFile  $favicon
     * @return void
     */
    public function updateFavicon(UploadedFile $favicon)
    {
        tap($this->favicon, function ($previous) use ($favicon) {
            $this->forceFill([
                'favicon_path' => $favicon->storePublicly(
                    'links-favicons', ['disk' => $this->faviconDisk()]
                ),
            ])->save();

            if ($previous) {
                Storage::disk($this->faviconDisk())->delete($previous);
            }
        });
    }

    /**
     * Get the URL to the user's profile photo.
     *
     * @return string
     */
    public function getFaviconUrlAttribute()
    {
        return $this->favicon_path
            ? Storage::disk($this->faviconDisk())->url($this->favicon_path)
            : null;
    }

    /**
     * Get the disk that favicon should be stored on.
     *
     * @return string
     */
    protected function faviconDisk()
    {
        return isset($_ENV['VAPOR_ARTIFACT_NAME']) ? 's3' : config('jetstream.profile_photo_disk', 'public');
    }

    /**
     * Delete the user's profile photo.
     *
     * @return void
     */
    public function deleteFavicon()
    {
        Storage::disk($this->faviconDisk())->delete($this->favicon_path);

        $this->forceFill([
            'favicon_path' => null,
        ])->save();
    }
}
