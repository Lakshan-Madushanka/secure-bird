<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\Media
 *
 * @method MediaData getData()
 * @property string $id
 * @property string $message_id
 * @property string $name
 * @property string $original_name
 * @property string $full_path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Message $message
 * @method static \Illuminate\Database\Eloquent\Builder|Media newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Media newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Media query()
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereFullPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereMessageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereOriginalName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereUpdatedAt($value)
 */
	class Media extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Message
 *
 * @method MessageData getData()
 * @property string $storagePath
 * @property string $textStoragePath
 * @property string $mediaStoragePath
 * @property string $url
 * @property string $id
 * @property string|null $text_path
 * @property string $password
 * @property int $no_of_allowed_visits
 * @property int $encryption_progress
 * @property bool $encryption_success
 * @property \Illuminate\Support\Carbon|null $expires_at
 * @property string|null $reference_mail
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Visit> $visits
 * @property-read int|null $visits_count
 * @method static \App\Builders\MessageBuilder|Message expired()
 * @method static \Database\Factories\MessageFactory factory($count = null, $state = [])
 * @method static \App\Builders\MessageBuilder|Message inValid()
 * @method static \App\Builders\MessageBuilder|Message newModelQuery()
 * @method static \App\Builders\MessageBuilder|Message newQuery()
 * @method static \App\Builders\MessageBuilder|Message notExpired()
 * @method static \App\Builders\MessageBuilder|Message query()
 * @method static \App\Builders\MessageBuilder|Message valid()
 * @method static \App\Builders\MessageBuilder|Message visitsExceeded()
 * @method static \App\Builders\MessageBuilder|Message visitsNotExceeded()
 * @method static \App\Builders\MessageBuilder|Message whereCreatedAt($value)
 * @method static \App\Builders\MessageBuilder|Message whereEncryptionProgress($value)
 * @method static \App\Builders\MessageBuilder|Message whereEncryptionSuccess($value)
 * @method static \App\Builders\MessageBuilder|Message whereExpiresAt($value)
 * @method static \App\Builders\MessageBuilder|Message whereId($value)
 * @method static \App\Builders\MessageBuilder|Message whereNoOfAllowedVisits($value)
 * @method static \App\Builders\MessageBuilder|Message wherePassword($value)
 * @method static \App\Builders\MessageBuilder|Message whereReferenceMail($value)
 * @method static \App\Builders\MessageBuilder|Message whereTextPath($value)
 * @method static \App\Builders\MessageBuilder|Message whereUpdatedAt($value)
 */
	class Message extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Visit
 *
 * @property string $id
 * @property string $message_id
 * @property string $ip_address
 * @property string $user_agent
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Message $message
 * @method static \Database\Factories\VisitFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Visit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Visit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Visit query()
 * @method static \Illuminate\Database\Eloquent\Builder|Visit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visit whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visit whereMessageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visit whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visit whereUserAgent($value)
 */
	class Visit extends \Eloquent {}
}

