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
 * App\Models\Category
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Document> $documents
 * @property-read int|null $documents_count
 * @method static \Illuminate\Database\Eloquent\Builder|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category query()
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereUpdatedAt($value)
 */
	class Category extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Comment
 *
 * @property int $id
 * @property int $user_id
 * @property int $document_id
 * @property string $content
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Document|null $documents
 * @property-read \App\Models\User|null $users
 * @method static \Illuminate\Database\Eloquent\Builder|Comment idRange($id)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment query()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereDocumentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereUserId($value)
 */
	class Comment extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Document
 *
 * @property int $id
 * @property int $user_id
 * @property int $category_id
 * @property string $title
 * @property string $slug
 * @property string|null $source_url
 * @property string|null $description
 * @property int $page_number
 * @property int $price
 * @property int $original_size Kich thuoc goc
 * @property string $original_format kick thuoc sau khi format
 * @property string|null $full_text
 * @property string|null $disks
 * @property string|null $path
 * @property string $type
 * @property string|null $language language code
 * @property string|null $country country code
 * @property int $helpful_count
 * @property int $unhelpful_count
 * @property int $viewed_count
 * @property int $downloaded_count
 * @property int $shared_count
 * @property int $active
 * @property int $is_public 0: private, 1: public
 * @property int $is_approved 0: wating, 1: yes, -1: no
 * @property int $can_download
 * @property string|null $approved_at
 * @property mixed|null $payload
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Category|null $categories
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Comment> $comments
 * @property-read int|null $comments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Download> $downloads
 * @property-read int|null $downloads_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Payment> $payments
 * @property-read int|null $payments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Rating> $ratings
 * @property-read int|null $ratings_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Report> $reports
 * @property-read int|null $reports_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tag> $tags
 * @property-read int|null $tags_count
 * @property-read \App\Models\User|null $user
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $user_bookmark
 * @property-read int|null $user_bookmark_count
 * @method static \Illuminate\Database\Eloquent\Builder|Document idRange($id)
 * @method static \Illuminate\Database\Eloquent\Builder|Document newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Document newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Document query()
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereApprovedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereCanDownload($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereDisks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereDownloadedCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereFullText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereHelpfulCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereIsApproved($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereIsPublic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereOriginalFormat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereOriginalSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document wherePageNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document wherePayload($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereSharedCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereSourceUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereUnhelpfulCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereViewedCount($value)
 */
	class Document extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Download
 *
 * @property int $id
 * @property int $user_id
 * @property int $document_id
 * @property array|null $payload
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Document|null $document
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Download idRange($id)
 * @method static \Illuminate\Database\Eloquent\Builder|Download newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Download newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Download query()
 * @method static \Illuminate\Database\Eloquent\Builder|Download whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Download whereDocumentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Download whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Download wherePayload($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Download whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Download whereUserId($value)
 */
	class Download extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Payment
 *
 * @property int $id
 * @property int $user_id
 * @property int $status
 * @property float $price
 * @property string $source Source : VNPay, Paypal,...
 * @property string $trading_code Mã giao dịch
 * @property string $transaction_id Số hóa đơn
 * @property string|null $message
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Document> $documents
 * @property-read int|null $documents_count
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Payment idRange($id)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Payment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Payment query()
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereTradingCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereUserId($value)
 */
	class Payment extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Rating
 *
 * @property-read \App\Models\Document|null $document
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Rating idRange($id)
 * @method static \Illuminate\Database\Eloquent\Builder|Rating newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Rating newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Rating query()
 */
	class Rating extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Report
 *
 * @property int $id
 * @property int $document_id
 * @property string $message
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Document|null $document
 * @method static \Illuminate\Database\Eloquent\Builder|Report idRange($id)
 * @method static \Illuminate\Database\Eloquent\Builder|Report newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Report newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Report query()
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereDocumentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereUpdatedAt($value)
 */
	class Report extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Tag
 *
 * @property int $id
 * @property string $name
 * @property string $normalized
 * @property string $slug
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Document> $documents
 * @property-read int|null $documents_count
 * @method static \Illuminate\Database\Eloquent\Builder|Tag idRange($id)
 * @method static \Illuminate\Database\Eloquent\Builder|Tag newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tag newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tag query()
 * @method static \Illuminate\Database\Eloquent\Builder|Tag whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tag whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tag whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tag whereNormalized($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tag whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tag whereUpdatedAt($value)
 */
	class Tag extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property int $active_status 0: not active, 1: active, 2: ban
 * @property string $avatar
 * @property string|null $avatar_disk
 * @property string|null $gender 0: none, 1: male, 2: female, 3: other
 * @property string|null $birthday
 * @property string|null $phone
 * @property string|null $address
 * @property float $money
 * @property string|null $country
 * @property string|null $language
 * @property string|null $social_id
 * @property string|null $social_type
 * @property int $total_save
 * @property int $total_downloaded
 * @property int $total_document
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Comment> $comments
 * @property-read int|null $comments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Document> $documents
 * @property-read int|null $documents_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Document> $documents_bookmark
 * @property-read int|null $documents_bookmark_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Download> $downloads
 * @property-read int|null $downloads_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Payment> $payments
 * @property-read int|null $payments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Rating> $ratings
 * @property-read int|null $ratings_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User role($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereActiveStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAvatarDisk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereBirthday($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereMoney($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSocialId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSocialType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTotalDocument($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTotalDownloaded($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTotalSave($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

