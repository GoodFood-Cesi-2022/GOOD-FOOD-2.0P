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
 * App\Models\Address
 *
 * @property-read \App\Models\User|null $createdBy
 * @method static \Illuminate\Database\Eloquent\Builder|Address newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Address newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Address query()
 */
	class Address extends \Eloquent implements \App\Contracts\CreatedByConstraint {}
}

namespace App\Models{
/**
 * App\Models\Email
 *
 * @property int $id
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\EmailFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Email newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Email newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Email query()
 * @method static \Illuminate\Database\Eloquent\Builder|Email whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Email whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Email whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Email whereUpdatedAt($value)
 */
	class Email extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\File
 *
 * @property int $id
 * @property string $uuid
 * @property string $name
 * @property string $path
 * @property int $size
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\FileFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|File newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|File newQuery()
 * @method static \Illuminate\Database\Query\Builder|File onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|File query()
 * @method static \Illuminate\Database\Eloquent\Builder|File whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereUuid($value)
 * @method static \Illuminate\Database\Query\Builder|File withTrashed()
 * @method static \Illuminate\Database\Query\Builder|File withoutTrashed()
 */
	class File extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Ingredient
 *
 * @property int $id
 * @property string $name
 * @property bool $allergen
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User|null $createdBy
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\IngredientType[] $types
 * @property-read int|null $types_count
 * @method static \Database\Factories\IngredientFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Ingredient newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ingredient newQuery()
 * @method static \Illuminate\Database\Query\Builder|Ingredient onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Ingredient query()
 * @method static \Illuminate\Database\Eloquent\Builder|Ingredient whereAllergen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ingredient whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ingredient whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ingredient whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ingredient whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ingredient whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ingredient whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Ingredient withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Ingredient withoutTrashed()
 */
	class Ingredient extends \Eloquent implements \App\Contracts\CreatedByConstraint {}
}

namespace App\Models{
/**
 * App\Models\IngredientIngredientType
 *
 * @property int $id
 * @property int $ingredient_id
 * @property int $ingredient_type_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|IngredientIngredientType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|IngredientIngredientType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|IngredientIngredientType query()
 * @method static \Illuminate\Database\Eloquent\Builder|IngredientIngredientType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IngredientIngredientType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IngredientIngredientType whereIngredientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IngredientIngredientType whereIngredientTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IngredientIngredientType whereUpdatedAt($value)
 */
	class IngredientIngredientType extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\IngredientType
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\IngredientTypeFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|IngredientType filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|IngredientType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|IngredientType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|IngredientType paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|IngredientType query()
 * @method static \Illuminate\Database\Eloquent\Builder|IngredientType simplePaginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|IngredientType whereBeginsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|IngredientType whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IngredientType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IngredientType whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IngredientType whereEndsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|IngredientType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IngredientType whereLike($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|IngredientType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IngredientType whereUpdatedAt($value)
 */
	class IngredientType extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Recipe
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property bool $star
 * @property string $base_price
 * @property int $recipe_type_id
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon $available_at
 * @property \Illuminate\Support\Carbon|null $trashed_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User|null $createdBy
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Ingredient[] $ingredients
 * @property-read int|null $ingredients_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\File[] $pictures
 * @property-read int|null $pictures_count
 * @property-read \App\Models\RecipeType $type
 * @method static \Illuminate\Database\Eloquent\Builder|Recipe available()
 * @method static \Database\Factories\RecipeFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Recipe filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Recipe newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Recipe newQuery()
 * @method static \Illuminate\Database\Query\Builder|Recipe onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Recipe paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Recipe query()
 * @method static \Illuminate\Database\Eloquent\Builder|Recipe simplePaginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Recipe toDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|Recipe whereAvailableAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Recipe whereBasePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Recipe whereBeginsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Recipe whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Recipe whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Recipe whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Recipe whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Recipe whereEndsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Recipe whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Recipe whereLike($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Recipe whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Recipe whereRecipeTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Recipe whereStar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Recipe whereTrashedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Recipe whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Recipe withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Recipe withoutTrashed()
 */
	class Recipe extends \Eloquent implements \App\Contracts\CreatedByConstraint {}
}

namespace App\Models{
/**
 * App\Models\RecipeIngredient
 *
 * @property int $id
 * @property int $recipe_id
 * @property int $ingredient_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|RecipeIngredient newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RecipeIngredient newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RecipeIngredient query()
 * @method static \Illuminate\Database\Eloquent\Builder|RecipeIngredient whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecipeIngredient whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecipeIngredient whereIngredientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecipeIngredient whereRecipeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecipeIngredient whereUpdatedAt($value)
 */
	class RecipeIngredient extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\RecipePicture
 *
 * @property int $id
 * @property int $recipe_id
 * @property int $file_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\RecipePictureFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|RecipePicture newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RecipePicture newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RecipePicture query()
 * @method static \Illuminate\Database\Eloquent\Builder|RecipePicture whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecipePicture whereFileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecipePicture whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecipePicture whereRecipeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecipePicture whereUpdatedAt($value)
 */
	class RecipePicture extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\RecipeType
 *
 * @property int $id
 * @property string $code
 * @method static \Illuminate\Database\Eloquent\Builder|RecipeType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RecipeType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RecipeType query()
 * @method static \Illuminate\Database\Eloquent\Builder|RecipeType whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecipeType whereId($value)
 */
	class RecipeType extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Role
 *
 * @property int $id
 * @property string $code
 * @method static \Illuminate\Database\Eloquent\Builder|Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Role query()
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereId($value)
 */
	class Role extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $firstname
 * @property string $lastname
 * @property string $phone
 * @property string|null $password
 * @property string|null $confirmable_token
 * @property int $email_id
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Client[] $clients
 * @property-read int|null $clients_count
 * @property-read \App\Models\Email $emailLogin
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Role[] $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Token[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User simplePaginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereBeginsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|User whereConfirmableToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEndsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFirstname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLike($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 */
	class User extends \Eloquent implements \Illuminate\Contracts\Auth\MustVerifyEmail, \App\Contracts\Users\HasRole, \App\Contracts\Users\ConfirmableToken {}
}

namespace App\Models{
/**
 * App\Models\UserRole
 *
 * @property int $id
 * @property int $user_id
 * @property int $role_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole whereUserId($value)
 */
	class UserRole extends \Eloquent {}
}

