<?php

namespace App\Nova;

use Illuminate\Validation\Rule;
use Laravel\Nova\Fields;
use Laravel\Nova\Http\Requests\NovaRequest;

class City extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\City>
     */
    public static $model = \App\Models\City::class;

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
                            return $query->where('state_id', $this->state_id);
                        }),
                ]),

            Fields\BelongsTo::make('State', resource: State::class)
                ->required()
                ->filterable()
                ->sortable(),
        ];
    }
}
