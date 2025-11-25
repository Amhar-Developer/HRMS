<?php

namespace App\Filament\Hr\Resources\Payrolls\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PayrollsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                ->searchable()
                ->toggleable()

                    ->sortable(),
                    TextColumn::make('user.employee_id')
                    ->label('Employee Code')
                    ->sortable()
                    ->toggleable()
                ->searchable(),
                TextColumn::make('user.department.name')
                    ->label('Department')
                    ->sortable()
                    ->toggleable()
                ->searchable(),
                TextColumn::make('user.position.title')
                    ->label('Position')
                    ->sortable()
                    ->toggleable()
                ->searchable(),
                TextColumn::make('month')
                ->toggleable()
                    ->searchable(),
                TextColumn::make('year')
                    ->numeric()
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('basic_salary')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                TextColumn::make('allowances')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                TextColumn::make('deductions')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                TextColumn::make('bonus')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                TextColumn::make('net_salary')
                    ->numeric()
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('status')
                ->toggleable()
                    ->searchable(),
                TextColumn::make('paid_at')
                    ->date()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
