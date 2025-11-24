<?php

namespace App\Filament\Hr\Resources\LeaveRequests\Schemas;

use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class LeaveRequestForm
{
    protected static function calculateDays(Set $set, Get $get): void
    {
        $start = $get('start_date');
        $end = $get('end_date');

        if (! $start || ! $end) {
            $set('days', null);       // or 'no_of_days' – your field name

            return;
        }

        $days = Carbon::parse($start)->diffInDays(Carbon::parse($end)) + 1;

        $set('days', $days);
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('leave_type_id')
                    ->relationship('leaveType', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                // DatePicker::make('start_date')
                //     ->live()
                //     ->afterStateUpdated(fn($state, Set $set,Get $get)=>
                //         self::calculateDays($set, $get))
                //     ->required(),
                // DatePicker::make('end_date')
                // ->live()
                //     ->required(),
                DatePicker::make('start_date')
                    ->live()
                    ->afterStateUpdated(function (Set $set, Get $get): void {
                        static::calculateDays($set, $get);
                    })
                    ->required(),

                DatePicker::make('end_date')
                    ->live()
                    ->afterStateUpdated(function (Set $set, Get $get): void {
                        static::calculateDays($set, $get);
                    })
                    ->required(),

                TextInput::make('days')
                    ->label('Number of Days')
                    ->numeric()
                    ->disabled()
                    ->dehydrated(),

                Textarea::make('reason')
                    ->columnSpanFull(),
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',

                    ])
                    ->native(false)
                    ->required()
                    // ->grouped()
                    // ->columnSpanFull()
                    ->default('pending') 
                    ->live(),
                // TextInput::make('approved_by')
                //     ->numeric(),
                DateTimePicker::make('approved_at'),
                Textarea::make('rejection_reason')
                ->default(null)
                    ->columnSpanFull()
                    ->visible(fn (Get $get) => $get('status') === 'rejected'),

            ]);

    }
}
