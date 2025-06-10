<?php

namespace App\Nova;

use Illuminate\Validation\Rule;
use Laravel\Nova\Fields;
use Laravel\Nova\Http\Requests\NovaRequest;

class Country extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Country>
     */
    public static $model = \App\Models\Country::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'name',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @return array<int, \Laravel\Nova\Fields\Field>
     */
    public function fields(NovaRequest $request): array
    {
        return [
            Fields\ID::make()->sortable(),

            Fields\Text::make('Name')
                ->sortable()
                ->maxlength(255, enforce: true)
                ->rules([
                    'required',
                    'min:3',
                    'max:255',
                    Rule::unique(self::$model)
                        ->ignore($this->resource ? $this->resource->id : null),
                ]),

            Fields\HasMany::make('States', resource: State::class),

            Fields\HasManyThrough::make('Cities', resource: City::class),
        ];
    }
}
