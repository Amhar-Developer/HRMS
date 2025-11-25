<?php

namespace App\Filament\Hr\Resources\Payrolls\Schemas;

use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class PayrollForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(function ($state, Set $set) {
                        $user = User::find($state);
                        if ($user) {
                            $set('basic_salary', $user->salary);
                        }
                    })
                    ->required(),
                Select::make('month')
                    ->options([
                        'January' => 'January',
                        'February' => 'February',
                        'March' => 'March',
                        'April' => 'April',
                        'May' => 'May',
                        'June' => 'June',
                        'July' => 'July',
                        'August' => 'August',
                        'September' => 'September',
                        'October' => 'October',
                        'November' => 'November',
                        'December' => 'December',
                    ])
                    ->required(),
                TextInput::make('year')
                    ->required()
                    ->default(date('Y'))
                    ->numeric(),
                TextInput::make('basic_salary')
                    ->required()
                    ->afterStateUpdated(fn ($state, Set $set, Get $get) => self::calculateNetSalary($set, $get))
                    ->live(onBlur: true),
                    // ->numeric(),
                TextInput::make('allowances')
                    ->required()
                    // ->numeric()
                    ->afterStateUpdated(fn ($state, Set $set, Get $get) => self::calculateNetSalary($set, $get))
                    ->live(onBlur: true),
                    // ->default(0),
                TextInput::make('deductions')
                    ->required()
                    // ->numeric()
                    ->afterStateUpdated(fn ($state, Set $set, Get $get) => self::calculateNetSalary($set, $get))
                    ->live(onBlur: true),
                    // ->default(0),
                TextInput::make('bonus')
                    ->required()
                    // ->numeric()
                    ->afterStateUpdated(fn ($state, Set $set, Get $get) => self::calculateNetSalary($set, $get))
                    ->live(onBlur: true),
                    // ->default(0),
                TextInput::make('net_salary')
                    ->required()
                    ->disabled()
                    ->dehydrated()
                    ->numeric(),
                // TextInput::make('status')
                //     ->required()
                //     ->default('draft'),
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'draft' => 'Draft',
                        'processed' => 'Processed',
                        'paid' => 'Paid',

                    ])
                    ->native(false)
                    ->required()
                   // ->grouped()
                   // ->columnSpanFull()
                    ->default('draft'),
                DatePicker::make('paid_at'),
            ]);
    }

    protected static function calculateNetSalary(Set $set, Get $get): void
    {
        $basicSalary = (float) ($get('basic_salary') ?? 0);
        $allowances = (float) ($get('allowances') ?? 0);
        $deductions = (float) ($get('deductions') ?? 0);
        $bonus = (float) ($get('bonus') ?? 0);

        $netSalary = $basicSalary + $allowances + $bonus - $deductions;

        $set('net_salary', $netSalary);
    }
}
