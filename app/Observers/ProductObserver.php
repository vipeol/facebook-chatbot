<?php

namespace App\Observers;

use App\Product;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProductObserver
{
        public function creating(Product $product)
        {
            if ($product->image_url->isValid()) {
                $this->upload($product);
            }
        }

        public function deleting(Product $product)
        {
            Storage::delete('products/' . $product->image_url);
        }

        public function updating(Product $product)
        {
            if (is_a($product->image_url, UploadedFile::class) and $product->image_url->isValid()) {
                $previous_image = $product->getOriginal('image_url');
                $this->upload($product);

                Storage::delete('products/' . $previous_image);
            }
        }

        protected function upload(Product $product)
        {
            $extension = $product->image_url->extension();
            $name = bin2hex(openssl_random_pseudo_bytes(8));
            $name = $name . '.' . $extension;

            $product->image_url->storeAs('products', $name);
            $product->image_url = $name;
        }

}