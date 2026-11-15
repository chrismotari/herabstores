<?php

namespace App\Filament\Imports;

use App\Models\Category;
use App\Models\Product;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;
use Illuminate\Support\Str;

/**
 * CSV columns: name, slug (optional), description, price, stock, category (slug or name), is_active (optional).
 * Rows are matched on slug, so re-importing updates existing products.
 */
class ProductImporter extends Importer
{
    protected static ?string $model = Product::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')->requiredMapping()->rules(['required', 'max:255'])->example('Kericho Gold Tea 500g'),
            ImportColumn::make('slug')->rules(['nullable', 'alpha_dash', 'max:255'])->example('kericho-gold-tea-500g'),
            ImportColumn::make('description')->rules(['nullable', 'max:5000'])->example('Premium Kenyan black tea.'),
            ImportColumn::make('price')->requiredMapping()->numeric()->rules(['required', 'numeric', 'min:0'])->example('450'),
            ImportColumn::make('stock')->integer()->rules(['nullable', 'integer', 'min:0'])->example('20'),
            ImportColumn::make('category')
                ->requiredMapping()
                ->relationship(resolveUsing: fn (string $state) => Category::where('slug', $state)
                    ->orWhereRaw('lower(name) = ?', [Str::lower($state)])->first())
                ->rules(['required'])
                ->example('tea'),
            ImportColumn::make('is_active')->label('Visible')->boolean()->rules(['nullable', 'boolean'])->example('yes'),
        ];
    }

    public function resolveRecord(): Product
    {
        $slug = filled($this->data['slug'] ?? null) ? $this->data['slug'] : Str::slug($this->data['name']);

        return Product::firstOrNew(['slug' => $slug]);
    }

    protected function beforeFill(): void
    {
        $this->data['slug'] = $this->record->slug;
        $this->data['description'] = filled($this->data['description'] ?? null)
            ? $this->data['description']
            : ($this->record->description ?? $this->data['name']);
    }

    /** Blank stock/visibility cells keep the current value (or the default for new products). */
    protected function beforeSave(): void
    {
        $this->record->stock ??= $this->record->getOriginal('stock') ?? 0;
        $this->record->is_active ??= $this->record->getOriginal('is_active') ?? true;

        if (! $this->record->exists) {
            $this->record->user_id = $this->import->user_id;
        }
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Product import finished: '.Number::format($import->successful_rows).' '.str('row')->plural($import->successful_rows).' imported.';

        if ($failed = $import->getFailedRowsCount()) {
            $body .= ' '.Number::format($failed).' '.str('row')->plural($failed).' failed.';
        }

        return $body;
    }
}
