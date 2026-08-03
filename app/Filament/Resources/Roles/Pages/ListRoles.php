<?php

declare(strict_types=1);

namespace App\Filament\Resources\Roles\Pages;

use App\Filament\Resources\Roles\RoleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRoles extends ListRecords
{
    protected static string $resource = RoleResource::class;

    public function getHeading(): string
    {
        return 'Daftar Peran';
    }

    public function getSubheading(): ?string
    {
        return 'Kelola peran dan hak akses pengguna.';
    }

    protected function getActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
