<?php

namespace App\Services;

use App\Models\Category;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductReview;
use App\Models\ProductTag;
use App\Models\Tag;
use App\Models\UserFavorite;
use App\Traits\StorageImageTrait;
use Illuminate\Http\Client\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ProductService
{
    use StorageImageTrait;

    const PRODUCT_PAGINATE = 30;
    private Product $product;
    private ProductImage $productImage;
    private Tag $tag;
    private ProductTag $productTag;
    private UserFavorite $userFavorite;
    private Category $category;
    private ProductReview $productReview;


    public function __construct(ProductReview $productReview, Category $category, Product $product, ProductImage $productImage, Tag $tag, ProductTag $productTag, UserFavorite $userFavorite)
    {
        $this->product = $product;
        $this->productImage = $productImage;
        $this->tag = $tag;
        $this->productTag = $productTag;
        $this->userFavorite = $userFavorite;
        $this->category = $category;
        $this->productReview = $productReview;
    }

    public function getModel()
    {
        return $this->product;
    }

    public function getProductSold($request){
        $product = Product::query()
            ->leftJoin('order_details','order_details.product_id','products.id')
            ->leftJoin('orders', 'orders.id', 'order_details.order_id')
            ->select('products.id','products.avatar','products.name','order_details.product_id',DB::raw('sum(quantity) as total_sold' ))
            ->groupBy('order_details.product_id','products.name','products.id');
        if(!empty($request->start_at)){
            $startAt = Carbon::createFromFormat('d/m/Y',$request->start_at)->format('Y-m-d');
            $product->where(DB::raw('date(order_details.created_at)'),'>=' , $startAt);
        }
        if(!empty($request->end_at)){
            $startAt = Carbon::createFromFormat('d/m/Y',$request->end_at)->format('Y-m-d');
            $product->where(DB::raw('date(order_details.created_at)'),'<=' , $startAt);
        }

        $product = $product->paginate(self::PRODUCT_PAGINATE);
        return $product;
    }


    public function search(Request $request)
    {
        $keyword = $request->get('keyword');
        if ($request->sort_price == 1) {
            $sort = ['product_attributes.promotional_price', 'desc'];
        } else if ($request->sort_price == 2) {
            $sort = ['product_attributes.promotional_price', 'asc'];
        } else {
            $sort = ['product_attributes.created_at', 'desc'];
        }
        $product = ProductsModel::join('product_attributes', 'products.id', '=', 'product_attributes.product_id')
            ->orderBy($sort[0], $sort[1])
            ->select('products.*', DB::raw('MAX(product_attributes.promotional_price) as max_promotional_price'))
            ->where('products.name', 'like', '%' . $keyword . '%')
            ->groupBy('product_attributes.product_id', 'product_attributes.created_at', 'product_attributes.promotional_price')
            ->orderBy('max_promotional_price', 'desc')
            ->paginate(20);
        foreach ($product as $item) {
            $item->infor = ProductInformationModel::find($item->product_infor_id);
            $item->type_product = ProductsModel::where('product_infor_id', $item->product_infor_id)->get();
            $item->price = ProductAttributesModel::where('product_id', $item->id)->first()->price;
            $this->flashSale($item);
            $this->starReview($item);
        }
        return view('web.product.search', compact('product', 'keyword'));
    }

    /**
     * Display a listing of Products
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function get()
    {
        $query = $this->product::query()
            ->latest()
            ->with(['category', 'tags', 'images']);

//        if ($searchByStock = request('filter_stock')) {
//            $this->filterByStock($searchByStock, $query, [10, 100, 200]);
//        }
//
//        $search = request('search');
//        $query->when($search, function ($query1) use ($search) {
//            $query1->whereHas('category', function ($query2) use ($search) {
//                $query2
//                    ->Where('product_categories.name', 'LIKE', "%{$search}%")
//                    ->orWhere('products.name', 'LIKE', "%{$search}%");
//            });
//        });


        return $query->get();
    }

    public function getRandom()
    {
        $query = $this->product::query()
            ->inRandomOrder()
            ->with(['category', 'tags', 'images']);

//        if ($searchByStock = request('filter_stock')) {
//            $this->filterByStock($searchByStock, $query, [10, 100, 200]);
//        }
//
//        $search = request('search');
//        $query->when($search, function ($query1) use ($search) {
//            $query1->whereHas('category', function ($query2) use ($search) {
//                $query2
//                    ->Where('product_categories.name', 'LIKE', "%{$search}%")
//                    ->orWhere('products.name', 'LIKE', "%{$search}%");
//            });
//        });


        return $query->paginate(self::PRODUCT_PAGINATE);
    }

    /**
     * Display a listing of Products To Array
     * @return array
     */
    public function getToArray($isArray = false)
    {
        $query = Product::query()
            ->join('product_categories', 'products.category_id', '=', 'product_categories.id')
            ->select(['products.id', 'products.name AS NameProduct', 'products.stock', 'product_categories.name', 'products.expired_at'])
            ->orderBy('products.id', 'DESC')
            ->get();
        if ($isArray) {
            $query->toArray();
            return $query;
        }
        return $query;
    }

    private function prepairData($request)
    {
        $params = $request->only([
            'name',
            'slug',
            'parent_id',
            'thumbnail',
            'status',
            'description'
        ]);
        if (!isset($request->parent_id)) {
            $params['parent_id'] = 0;
        }
        $params['slug'] = Str::slug($request->name);
        return $params;
    }

    public function calculatePriceDiscount($price, $percent)
    {
        return $price - ($price * ($percent / 100));
    }

    /**
     * Insert Product
     * @param ImageService $imageService
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    public function createProduct($request)
    {
        $data = [
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'stock' => $request->stock,
            'expired_at' => $request->expired_at,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'price' => $request->price,
            'brand_id' => $request->brand_id,
            'user_id' => auth()->id(),
            'discount' => $request->discount,
            'weight' => $request->weight,
            'sale_status' => $request->sale_status,
            'status' => $request->status
        ];
        if ($request->sku) {
            $data['sku'] = $request->sku;
        }else{
            $data['sku'] = Str::random(10);
        }
        // Store avatar image
        if ($request->hasFile('avatar')) {
            $avatar = $request->avatar;
            $data['avatar'] = $this->uploadFile($avatar, PRODUCT_DIR . '/' . auth()->id() . '/' . Str::random(30) . "." . $avatar->getClientOriginalExtension());
        }

        $product = $this->product->query()->create($data);

        // Sale Price
        if ($product->discount) {
            $product->sale_price = $this->calculatePriceDiscount($product->price, $product->discount);
            $product->save();
        }

        // Insert data to images table
        if ($request->hasFile('image_path')) {
            $imagePath = $request->image_path;
            foreach ($imagePath as $index => $image) {
                $dataProductImageDetail = $this->uploadFile($image, PRODUCT_DIR . '/' . auth()->id() . '/' . Str::random(30) . "." . $image->getClientOriginalExtension());
                $imageCreated = $this->image->create([
                    'name' => $image->getClientOriginalName(),
                    'image_path' => $dataProductImageDetail,
                    'size' => $image->getSize() / 1024   // KB
                ]);

                // Insert data to product_images table
                $product->images()->create([
                    'image_id' => $imageCreated->id,
                    'sort_order' => $index + 1
                ]);
            }
        }

        // Insert data to tags table
        $tags = $request->tags;
        if (!empty($tags)) {
            foreach ($tags as $tag) {
                $idTags[] = $this->tag->firstOrCreate(['name' => $tag])->id;
            }
            $product->tags()->attach($idTags);
        }


        // Insert to tags table
        return $product;
    }

    /**
     * Get Product via ID
     * @param int $id
     * @return Builder|Builder[]|Collection|Model
     */
    public function findItem($column, $value)
    {
        return $this->product::query()->with(['category', 'tags', 'images', 'brand'])->where($column, $value)->firstOrFail();
    }

    /**
     * Update Product
     * @param int $id ,
     * @param UpdateProductRequest $request
     * @param ImageService $imageService
     * @return bool
     */
    public function updateProduct($request, $id)
    {
        //--- 1. Update user ---
        $product = $this->product->findOrFail($id);
        $data = [
            'sku' => $request->sku,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'stock' => $request->stock,
            'expired_at' => $request->expired_at,
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'description' => $request->description,
            'price' => $request->price,
            'discount' => $request->discount,
            'weight' => $request->weight,
            'user_id' => auth()->id(),
            'sale_status' => $request->sale_status,
            'status' => $request->status
        ];

        $old_avatar_path = null;
        if ($request->hasFile('avatar')) {
            $old_avatar_path = $product->avatar;
            $avatar = $request->avatar;
            $data['avatar'] = $this->uploadFile($avatar, PRODUCT_DIR . '/' . auth()->id() . '/' . Str::random(30) . "." . $avatar->getClientOriginalExtension());
        }

        $product->update($data);

        // Sale Price
        if ($product->discount) {
            $product->sale_price = $this->calculatePriceDiscount($product->price, $product->discount);
            $product->save();
        }


        // --- 2.Remove old file avatar ---
        if ($old_avatar_path) {
            // Remove old file
            $this->deleteFile($old_avatar_path);
        }

        // Insert data to images table
        if ($request->hasFile('image_path')) {
            $old_image_detail_path = $this->productImage->where('product_id', $id)->get()->pluck('image_id')->toArray();

            // Remove old file image detail
            $this->productImage->where('product_id', $id)->delete();

            $imagePath = $request->image_path;
            foreach ($imagePath as $index => $image) {
                $dataProductImageDetail = $this->uploadFile($image, PRODUCT_DIR . '/' . auth()->id() . '/' . Str::random(30) . "." . $image->getClientOriginalExtension());

                // Insert data to product_images table
                $product->images()->create([
                    'name' => $image->getClientOriginalName(),
                    'image_path' => $dataProductImageDetail,
                    'size' => $image->getSize() / 1024,   // KB
                    'sort_order' => $index + 1
                ]);
            }
        }

        // Insert data to tags table
        $tags = $request->tags;
        if (!empty($tags)) {
            foreach ($tags as $tag) {
                $idTags[] = $this->tag->firstOrCreate(['name' => $tag])->id;
            }
            $product->tags()->sync($idTags);
        }
    }

    /**
     * Delete Product
     * @param int $id
     * @return bool
     */
    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $this->findItem('id', $id)->delete();
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Message: {$e->getMessage()}. Line: {$e->getLine()}");
            return false;
        }
    }

    public function getProductRelated($id, $categoryId)
    {
        return $this->product->query()->where('category_id', $categoryId)->where('id', '!=', $id)->get();
    }

    /**
     * Export Product CSV
     * @return BinaryFileResponse
     */
    public function exportCSV()
    {
        return Excel::download(new ProductExport($this), 'products-csv.csv');
    }

    /**
     * Export Product PDF
     * @return Response
     */
    public function exportPDF()
    {
        $products = collect($this->getToArray());
        $mytime = Carbon::now()->isoFormat('DD/MM/YYYY');
        $pdf = Pdf::loadView('pdf.product.products', [
            'products' => $products,
            'title' => "Products List",
            'mytime' => $mytime
        ]);
        return $pdf->download('products.pdf');
    }

    /**
     * Filter stock
     * @param string $searchValue
     * @param string $query
     * @param array $range
     * @return void
     */
    private function filterByStock($searchValue, $query, $range)
    {
        if ($searchValue == 1) {
            $query->where('stock', '<', $range[0]);
        } else if ($searchValue == 2) {
            $query->WhereBetween('stock', [$range[0], $range[1]]);
        } else if ($searchValue == 3) {
            $query->WhereBetween('stock', [$range[1], $range[2]]);
        } else {
            $query->where('stock', '>', $range[2]);
        }
    }

    public function getProductFavorite()
    {
        $user = Auth::user();
        if (!empty($user->favorites)) {
            return $user->favorites;
        }
        return [];
    }

    public function createFavoriteProduct($productId)
    {
        DB::beginTransaction();
        try {
            $productFavorite = $this->userFavorite::query()->create([
                'user_id' => Auth::user()->id,
                'product_id' => $productId
            ]);
            DB::commit();
            return response()->json([
                'status' => 200,
                'data' => $productFavorite
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Message: {$e->getMessage()}. Line: {$e->getLine()}");
            return response()->json([
                'status' => 500,
                'data' => []
            ], 500);
        }
    }

    public function removeFavoriteProduct($productId)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();
            $this->userFavorite::query()->where([
                'product_id' => $productId,
                'user_id' => $user->id
            ])->delete();
            DB::commit();
            return response()->json([
                'status' => 200,
                'data' => true
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Message: {$e->getMessage()}. Line: {$e->getLine()}");
            return response()->json([
                'status' => 500,
                'data' => trans('messages.server_error')
            ], 500);
        }
    }

    public function checkFavoriteProduct($productId)
    {
        try {
            if (!Auth::check()) {
                return response()->json([
                    'status' => Response::HTTP_NOT_FOUND,
                ]);
            }
            $favoriteProduct = $this->userFavorite::query()->where([
                'user_id' => Auth::user()->id,
                'product_id' => $productId
            ])->first();
            if ($favoriteProduct) {
                return response()->json([
                    'status' => 200,
                    'data' => true
                ]);
            }
            return response()->json([
                'status' => 200,
                'data' => false
            ]);


        } catch (\Exception $e) {
            Log::error("Message: {$e->getMessage()}. Line: {$e->getLine()}");
            return response()->json([
                'status' => 500,
                'data' => trans('messages.server_error')
            ], 500);
        }
    }

    public function filterProductCategory($request, $categoryId)
    {
        try {
            $tagsId = $request->tagsId ?? [];
            $brandsId = $request->brandsId ?? [];
            $orderPrice = $request->orderPrice;
            $categoryBySlug = $this->category->where('id', $categoryId)->firstOrFail();
            $products = $this->product::query()->where('category_id', $categoryId);
            if (!empty($tagsId)) {
                $products->whereHas('tags', function ($query) use ($tagsId) {
                    $query->whereIn('tag_id', $tagsId);
                });
            }
            if (!empty($brandsId)) {
                $products->whereIn('brand_id', $brandsId);
            }

            if (!empty($orderPrice)) {
                if ($orderPrice == Product::ORDER_PRICE_LOW_TO_HIGH) {
                    $products->orderBy('price');
                } else {
                    $products->orderBy('price', 'DESC');
                }
            }

            $products = $products->paginate(self::PRODUCT_PAGINATE);
            if ($products->isEmpty()) {
                return response()->json([
                    'status' => Response::HTTP_NOT_FOUND,
                ]);
            }
            $productsHtml = view('frontend.products.components.list-product-filter', compact('products', 'categoryBySlug'))->render();
            return response()->json([
                'status' => 200,
                'data' => $products,
                'html' => $productsHtml
            ]);

        } catch (\Exception $e) {
            Log::error("Message: {$e->getMessage()}. Line: {$e->getLine()}");
            return response()->json([
                'status' => 500,
                'data' => trans('messages.server_error')
            ], 500);
        }
    }

    public function ratingProduct($request, $productId)
    {
        DB::beginTransaction();
        try {
            if (empty($request->comment)) {
                return response()->json([
                    'status' => 500,
                    'data' => "Vui lòng nhập bình luận"
                ], 500);
            }
            $user = Auth::user();
            $productReview = $this->productReview;
            $productReview->product_id = $productId;
            $productReview->user_id = $user->id;
            $productReview->comment = $request->comment;
            $productReview->rating = $request->ratingStar;
            $productReview->save();
            DB::commit();
            return response()->json([
                'status' => 200,
                'data' => true
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Message: {$e->getMessage()}. Line: {$e->getLine()}");
            return response()->json([
                'status' => 500,
                'data' => trans('messages.server_error')
            ], 500);
        }
    }

    public function getProductReview($productId)
    {
        $productReview = $this->productReview::query()
            ->with(['user', 'admin'])
            ->where('product_id', $productId)
            ->where('status', 1)
            ->get();
        return $productReview;
    }

    public function avgRatingProductReview($productId)
    {
        $productReview = $this->productReview::query()
            ->where(['product_id' => $productId, 'status' => 1])
            ->avg('rating');
        return $productReview;
    }

    public function avgEachRatingProductReview($productId)
    {
        $ratingsCount = $this->productReview::selectRaw('rating, COUNT(*) as count')
            ->groupBy('rating')
            ->orderBy('rating', 'DESC')
            ->where(['product_id' => $productId, 'status' => 1])
            ->get();
        $totalCount = $ratingsCount->sum('count');
        $percentageData = $ratingsCount->map(function ($item) use ($totalCount) {
            return [
                'rating' => $item->rating,
                'percent' => round(($item->count / $totalCount) * 100),
            ];
        });
        return $percentageData;
    }

    public function getProductReviewPaginate()
    {
        $productReview = $this->productReview::query()
            ->with(['user', 'product'])
            ->whereNull('censorship_user_id')
            ->orderBy('status', 'ASC')
            ->paginate(self::PRODUCT_PAGINATE);
        return $productReview;
    }

    public function changeStatusComment($productId, $commentId)
    {
        $productReview = $this->productReview::query()->where(['product_id' => $productId, 'id' => $commentId])->first();
        if ($productReview) {
            if ($productReview->status == 0) {
                $productReview->status = 1;
            } else {
                $productReview->status = 0;
            }
            $productReview->save();
            return true;
        }
        return false;
    }

    public function adminReplyComment($request, $productId, $commentId)
    {
        $productReview = new ProductReview();
        $productReview->comment = $request->comment;
        $productReview->parent_comment = $commentId;
        $productReview->product_id = $productId;
        $productReview->censorship_user_id = Auth::guard('admin')->user()->id;
        $productReview->save();
    }

    public function deleteComment($productId, $commentId){
        DB::beginTransaction();
        try {
            $this->productReview::query()->where(['product_id' => $productId,'id'=>$commentId])->first()->delete();
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Message: {$e->getMessage()}. Line: {$e->getLine()}");
            return false;
        }
    }
}
