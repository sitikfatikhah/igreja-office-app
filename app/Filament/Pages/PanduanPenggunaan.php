<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class PanduanPenggunaan extends Page
{
    protected string $view = 'filament.pages.panduan-penggunaan';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::QuestionMarkCircle;
}
