<?php

namespace App\Filament\Hr\Resources\PerformanceReviews\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class PerformanceReviewForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Employee Information')
                    ->columns(3)
                    ->schema([
                        Select::make('user_id')
                            ->relationship('user', 'name')
                            ->required(),
                        Select::make('reviewer_id')
                            ->relationship('reviewer', 'name')
                            ->required(),
                        TextInput::make('review_period')
                            ->default(now()->format('Y-m-d'))
                            ->placeholder('Select Review Period')
                            ->required(),
                    ]),

                Section::make('Performance Scores')
                    ->columns(2)
                    ->schema([
                        TextInput::make('quality_of_work')
                            ->required()
                            ->afterStateUpdated(fn ($state, Set $set, Get $get) => self::calculateOverAllRating($set, $get))
                            ->hint('1 – 10')
                            ->live()
                            ->minValue(1)
                            ->maxValue(10)
                            ->numeric(),
                        TextInput::make('productivity')
                            ->required()
                            ->afterStateUpdated(fn ($state, Set $set, Get $get) => self::calculateOverAllRating($set, $get))
                            ->hint('1 – 10')
                            ->live()
                            ->minValue(1)
                            ->maxValue(10)
                            ->numeric(),
                        TextInput::make('communication')
                            ->required()
                            ->afterStateUpdated(fn ($state, Set $set, Get $get) => self::calculateOverAllRating($set, $get))
                            ->hint('1 – 10')
                            ->live()
                            ->minValue(1)
                            ->maxValue(10)
                            ->numeric(),
                        TextInput::make('teamwork')
                            ->required()
                            ->afterStateUpdated(fn ($state, Set $set, Get $get) => self::calculateOverAllRating($set, $get))
                            ->hint('1 – 10')
                            ->live()
                            ->minValue(1)
                            ->maxValue(10)
                            ->numeric(),
                        TextInput::make('leadership')
                            ->required()
                            ->afterStateUpdated(fn ($state, Set $set, Get $get) => self::calculateOverAllRating($set, $get))
                            ->hint('1 – 10')
                            ->live()
                            ->minValue(1)
                            ->maxValue(10)
                            ->numeric(),
                        TextInput::make('overall_rating')
                            ->required()
                            ->afterStateUpdated(fn ($state, Set $set, Get $get) => self::calculateOverAllRating($set, $get))
                            ->hint('Average Score')
                            ->suffix('/ 10')
                            ->live()
                            ->dehydrated()
                            ->disabled()
                            ->minValue(1)
                            ->maxValue(10)
                            ->numeric(),
                    ]),

                Section::make('Performance Summary')
                    ->columns(1)
                    ->schema([
                        Textarea::make('strengths')
                            ->columnSpanFull(),
                        Textarea::make('area_for_improvement')
                            ->columnSpanFull(),
                    ]),

                Section::make('Development & Remarks')
                    ->columns(1)
                    ->schema([
                        Textarea::make('goals')
                            ->columnSpanFull(),
                        Textarea::make('comments')
                            ->columnSpanFull(),
                    ]),
            ]);
        
    }
    public static function calculateOverAllRating(Set $set, Get $get){
            
        $QualityOfWork = (int)$get('quality_of_work') ?? 0;
        $Productivity = (int)$get('productivity') ?? 0;
        $Communication = (int)$get('communication') ?? 0;
        $Teamwork = (int)$get('teamwork') ?? 0;
        $Leadership = (int)$get('leadership') ?? 0;

        $OverAllRating = ($QualityOfWork + $Productivity + $Communication + $Teamwork + $Leadership) / 5;
        $set('overall_rating', round($OverAllRating, 2));
    }
}
