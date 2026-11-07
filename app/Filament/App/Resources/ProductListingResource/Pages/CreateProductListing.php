<?php

namespace App\Filament\App\Resources\ProductListingResource\Pages;

use App\Filament\App\Resources\ProductListingResource;
use App\Models\Product;
use App\Services\Ai\FakeListingGenerator;
use Filament\Resources\Pages\CreateRecord;

class CreateProductListing extends CreateRecord
{
    protected static string $resource = ProductListingResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Auto-generate fields if left empty
        if (empty($data['title']) && !empty($data['product_id'])) {
            $product = Product::find($data['product_id']);
            if ($product) {
                $generated = FakeListingGenerator::generate($product, [
                    'marketplace' => $data['marketplace'] ?? 'amazon',
                    'locale' => $data['locale'] ?? 'en',
                ]);
                $data = array_merge($generated, $data);
            }
        }

        $data['workspace_id'] = \Filament\Facades\Filament::getTenant()->id;

        return $data;
    }
}
