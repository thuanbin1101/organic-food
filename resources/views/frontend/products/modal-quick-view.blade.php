<!-- Quick view -->
<div class="modal fade custom-modal" id="quickViewModal{{$key}}" tabindex="-1" aria-labelledby="quickViewModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 col-sm-12 col-xs-12 mb-md-0 mb-sm-5">
                        <div class="detail-gallery">
                            <span class="zoom-icon"><i class="fi-rs-search"></i></span>
                            <!-- MAIN SLIDES -->
                            <div class="product-image-slider">
                                <figure class="border-radius-10">
                                    <img src="{{asset('/storage/'.$item->avatar)}}"
                                         alt="product image"/>
                                </figure>
                                @foreach($item->images as $image)
                                    <figure class="border-radius-10">
                                        <img src="{{asset('/storage/'.$image->image_path)}}"
                                             alt="product image"/>
                                    </figure>
                                @endforeach
                            </div>
                            <!-- THUMBNAILS -->
                            <div class="slider-nav-thumbnails">
                                <div>
                                    <img height="100px" width="250px"
                                         src="{{asset('/storage/'.$item->avatar)}}"
                                         alt="product image"/>
                                </div>
                                @foreach($item->images as $image)
                                    <div><img height="100px" width="250px"
                                              src="{{asset('/storage/'.$image->image_path)}}"
                                              alt="product image"/>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <!-- End Gallery -->
                    </div>
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <div class="detail-info pr-30 pl-30">
                            <span class="stock-status out-stock"> Sale Off </span>
                            <h3 class="title-detail"><a href="{{route('products.detail',$item->slug)}}"
                                                        class="text-heading"> <a
                                        href="{{route('products.detail',$item->slug)}}">{{$item->name}}
                                        / {{$item->weight}}</a></a></h3>
                            <div class="product-detail-rating">
                                <div class="product-rate-cover text-end">
                                    <div class="product-rate d-inline-block">
                                        <div class="product-rating" style="width: 90%"></div>
                                    </div>
                                    <span class="font-small ml-5 text-muted"> (32 reviews)</span>
                                </div>
                            </div>
                            <div class="clearfix product-price-cover">
                                <div class="product-price primary-color float-left">
                                    <span class="current-price text-brand">{!!$item->formatPrice()!!}</span>
                                    <span>
                                            <span class="save-price font-md color3 ml-15">{{$item->showDiscount()}}</span>
                                          @if($item->sale_status)
                                            <span
                                                class="old-price">{!!$item->getBasePrice()!!}</span>
                                           @endif
                                        </span>
                                </div>
                            </div>
                            <div class="detail-extralink mb-30">
                                <div class="detail-qty border radius">
                                    <a href="#" class="qty-down cart-qty-down"><i
                                            class="fi-rs-angle-small-down"></i></a>
                                    <input type="text" name="quantity" class="qty-val" value="1"
                                           min="1">
                                    <a href="#" class="qty-up cart-qty-up"><i
                                            class="fi-rs-angle-small-up"></i></a>
                                </div>
                                <div class="product-extra-link2">
                                    <button
                                        data-url="{{ route('cart.add',['productId'=>$product->id])}}"
                                        type="submit" class="button button-add-to-cart"><i
                                            class="fi-rs-shopping-cart"></i>{{trans('messages.cart.add_to_cart')}}
                                    </button>
                                </div>
                            </div>
                            <div class="font-xs">
                                <ul>
                                    <li class="mb-5">Brand: <span
                                            class="text-brand">{{optional($item->brand)->name}}</span></li>
                                </ul>
                            </div>
                        </div>
                        <!-- Detail Info -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
