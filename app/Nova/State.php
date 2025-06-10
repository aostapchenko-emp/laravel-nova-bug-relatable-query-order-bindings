<?php

namespace App\Nova;

use Illuminate\Validation\Rule;
use Laravel\Nova\Fields;
use Laravel\Nova\Http\Requests\NovaRequest;

class State extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\State>
     */
    public static $model = \App\Models\State::class;

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
                        ->ignore($this->resource ? $this->resource->id : null)
                        ->where(function ($query) {
                            return $query->where('country_id', $this->state_id);
                        }),
                ]),

            Fields\BelongsTo::make('Country', resource: Country::class)
                ->sortable()
                ->required(),

            Fields\HasMany::make('Cities', resource: City::class),
        ];
    }
}
