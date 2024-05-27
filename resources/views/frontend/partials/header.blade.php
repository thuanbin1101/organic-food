<header class="header-area header-style-1 header-height-2">
    <div class="mobile-promotion">
        <span>Grand opening, <strong>up to 15%</strong> off all items. Only <strong>3 days</strong> left</span>
    </div>
    <div class="header-top header-top-ptb-1 d-none d-lg-block">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-xl-3 col-lg-4">
                    <div class="header-info">
                        <ul>
                            <li><a href="page-about.htlm">{{ trans('messages.header.about_us') }}</a></li>
                            <li><a href="javascript:void(0)">{{ trans('messages.header.account') }}</a></li>
                            <li><a href="{{route('wishlist')}}">{{ trans('messages.header.wishlist') }}</a></li>
                            <li><a href="shop-order.html">{{ trans('messages.header.order_tracking') }}</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-4">
                    <div class="text-center">
                        <div id="news-flash" class="d-inline-block">
                            <ul>
                                <li>100% Secure delivery without contacting the courier</li>
                                <li>Supper Value Deals - Save more with coupons</li>
                                <li>Trendy 25silver jewelry, save up 35% off today</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4">
                    <div class="header-info header-info-right">
                        <ul>
                            <li>{{ trans('messages.header.need_help') }} <strong class="text-brand"> + 1800 900</strong>
                            </li>
                            <li>
                                @if (session()->get('website_language') == 'en')
                                    <a class="language-dropdown-active"
                                       href="{{ route('user.change-language', ['locale' => 'en']) }}">
                                        <img height="10px" src="{{ asset('backend/assets/images/flags/us.jpg') }}"
                                             alt="">
                                        English <i class="fi-rs-angle-small-down"></i></a>
                                    <ul class="language-dropdown">
                                        <li>
                                            <a href="{{ route('user.change-language', ['locale' => 'vi']) }}"
                                               class="d-flex"><img height="10px"
                                                                   src="{{ asset('frontend/assets/imgs/home/vn.svg') }}"
                                                                   alt=""/>Vietnamese</a>
                                        </li>
                                    </ul>
                                @else
                                    <a class="language-dropdown-active"
                                       href="{{ route('user.change-language', ['locale' => 'vi']) }}">
                                        <img height="10px" src="{{ asset('frontend/assets/imgs/home/vn.svg') }}"
                                             alt="">
                                        Vietnamese <i class="fi-rs-angle-small-down"></i></a>
                                    <ul class="language-dropdown">
                                        <li>
                                            <a href="{{ route('user.change-language', ['locale' => 'en']) }}"
                                               class="d-flex"><img height="10px"
                                                                   src="{{ asset('backend/assets/images/flags/us.jpg') }}"
                                                                   alt=""/>English</a>
                                        </li>
                                    </ul>
                                @endif

                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="header-middle header-middle-ptb-1 d-none d-lg-block">
        <div class="container">
            <div class="header-wrap">
                <div class="logo logo-width-1">
                    <a href="{{ route('home') }}"><img src="{{ asset('frontend/assets/imgs/home/logo-vip.png') }}"
                                                       alt="logo"/></a>
                </div>
                <div class="header-right">
                    <div class="search-style-2">
                        <form action="{{ route('products.search') }}" method="GET">
                            <select name="category" class="select-active">
                                <option value="0">{{ trans('messages.header.all_categories') }}</option>
                                @foreach ($categories as $category)
                                    <option {{ request()->get('category') == $category->id ? 'selected' : '' }}
                                            value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <input value="{{ request()->get('search') ? request()->get('search') : '' }}"
                                   type="text" name="search" placeholder="Search for items..."/>
                        </form>
                    </div>
                    <div class="header-action-right">
                        <div class="header-action-2">
{{--                            <div class="header-action-icon-2">--}}
{{--                                <a href="shop-compare.html">--}}
{{--                                    <img class="svgInject" alt="Nest"--}}
{{--                                         src="{{ asset('frontend/assets/imgs/theme/icons/icon-compare.svg') }}"/>--}}
{{--                                    <span class="pro-count blue">3</span>--}}
{{--                                </a>--}}
{{--                                <a href="shop-compare.html"><span--}}
{{--                                        class="lable ml-0">{{ trans('messages.header.compare') }}</span></a>--}}
{{--                            </div>--}}
                            <div class="header-action-icon-2">
                                <a href="{{route('wishlist')}}">
                                    <img class="svgInject" alt="Nest"
                                         src="{{ asset('frontend/assets/imgs/theme/icons/icon-heart.svg') }}"/>
                                </a>
                                <a href="{{route('wishlist')}}"><span
                                        class="lable">{{ trans('messages.header.wishlist') }}</span></a>
                            </div>
                            @include('frontend.carts.components.cart-header-dropdown')

                            <div class="header-action-icon-2">
                                <a href="javascript:void(0)">
                                    @auth
                                        <img class="svgInject rounded-circle" width="25px" height="25px" alt="Nest"
                                             src="{{\App\Helpers\Common::getImage(auth()->user()->avatar)}}"/>
                                    @endauth
                                    @guest
                                        <img class="svgInject" alt="Nest"
                                             src="{{ asset('frontend/assets/imgs/theme/icons/icon-user.svg') }}"/>
                                    @endguest
                                </a>
                                <a href="javascript:void(0)"><span
                                        class="lable ml-0">{{ trans('messages.header.account') }}</span></a>
                                <div class="cart-dropdown-wrap cart-dropdown-hm2 account-dropdown">
                                    @auth
                                        <ul>
                                            <li>
                                                <a href="{{route('account.profile')}}"><i
                                                        class="fi fi-rs-user mr-10"></i>{{ trans('messages.header.account') }}
                                                </a>
                                            </li>
{{--                                            <li>--}}
{{--                                                <a href="page-account.html"><i--}}
{{--                                                        class="fi fi-rs-location-alt mr-10"></i>{{ trans('messages.header.order_tracking') }}--}}
{{--                                                </a>--}}
{{--                                            </li>--}}
                                            {{-- <li>
                                                <a href="page-account.html"><i class="fi fi-rs-label mr-10"></i>My
                                                    Voucher</a>
                                            </li> --}}
                                            <li>
                                                <a href="{{route('wishlist')}}"><i
                                                        class="fi fi-rs-heart mr-10"></i>{{ trans('messages.header.wishlist') }}
                                                </a>
                                            </li>
                                            {{-- <li>
                                                <a href="page-account.html"><i
                                                        class="fi fi-rs-settings-sliders mr-10"></i>Setting</a>
                                            </li> --}}
                                            <li>
                                                <a href="{{ route('user.logout') }}"><i
                                                        class="fi fi-rs-sign-out mr-10"></i>{{ trans('messages.auth.logout') }}
                                                </a>
                                            </li>
                                        </ul>
                                    @else
                                        <ul>
                                            <li>
                                                <a href="{{ route('user.login') }}"><i class="fi fi-rs-user mr-10"></i>
                                                    {{ trans('messages.auth.login') }}</a>
                                            </li>
                                            <li>
                                                <a href="{{ route('user.register') }}"><i
                                                        class="fi fi-rs-user-add mr-10"></i>
                                                    {{ trans('messages.auth.register') }}</a>
                                            </li>
                                        </ul>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="header-bottom header-bottom-bg-color sticky-bar">
        <div class="container">
            <div class="header-wrap header-space-between position-relative">
                <div class="logo logo-width-1 d-block d-lg-none">
                    <a href="index.html"><img src="{{ asset('frontend/assets/imgs/theme/logo.svg') }}"
                                              alt="logo"/></a>
                </div>
                <div class="header-nav d-none d-lg-flex">
                    <div class="main-categori-wrap d-none d-lg-block">
                        <a class="categories-button-active" href="#">
                            <span class="fi-rs-apps"></span> {{ trans('messages.header.all_categories') }}
                            <i class="fi-rs-angle-down"></i>
                        </a>
                        <div class="categories-dropdown-wrap categories-dropdown-active-large font-heading">
                            <div class="categori-dropdown-inner">
                                <ul>
                                    @foreach ($categories as $category)
                                        <li>
                                            <a
                                                href="{{ route('products.listProduct', ['slug' => $category->slug]) }}">
                                                <img src="{{ asset('/storage/' . $category->avatar) }}"
                                                     alt=""/>{{ $category->name }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            {{-- <div class="more_categories"><span class="icon"></span> <span class="heading-sm-1">Show
                                    more...</span>
                            </div> --}}
                        </div>
                    </div>
                    <div class="main-menu main-menu-padding-1 main-menu-lh-2 d-none d-lg-block font-heading">
                        <nav>
                            <ul>
                                @foreach ($menus as $key => $menu)
                                    @php
                                        $classActive = '';
                                        $url = '';
                                        switch ($menu->name) {
                                            case 'Home':
                                                $classActive = $nameRoute == 'home' ? 'active' : '';
                                                $url = route('home');
                                                break;
                                            case 'Shop':
                                                $classActive = $nameRoute == 'shop' ? 'active' : '';
                                                $url = route('shop');
                                                break;
                                            case 'Contact':
                                                $classActive = $nameRoute == 'contact' ? 'active' : '';
                                                $url = route('contact');
                                                break;
                                            case 'Blogs':
                                                $classActive = $nameRoute == 'blogs' ? 'active' : '';
                                                $url = route('blogs');
                                                break;
                                            case 'FAQ':
                                                $classActive = $nameRoute == 'faq' ? 'active' : '';
                                                $url = route('faq');
                                                break;
                                            case 'Brands':
                                                $classActive = $nameRoute == 'brands' ? 'active' : '';
                                                $url = route('brands.index');
                                                break;
                                            default:
                                                $classActive = $nameRoute == 'home' ? 'active' : '';
                                                $url = route('home');
                                        }
                                    @endphp
                                    <li>
                                        @if(session('website_language') == "en")
                                            <a class="{{ $classActive }}"
                                               href="{{ $url }}">{{ $menu->name }}

                                                @if ($menu->children->isNotEmpty())
                                                    <i class="fi-rs-angle-down"></i>
                                                @endif
                                            </a>
                                        @else
                                            <a class="{{ $classActive }}"
                                               href="{{ $url }}">{{ $menu->vi_name }}
                                                @if ($menu->children->isNotEmpty())
                                                    <i class="fi-rs-angle-down"></i>
                                                @endif
                                            </a>
                                        @endif

                                        @if ($menu->children->isNotEmpty())
                                            <ul class="sub-menu">
                                                @foreach ($menu->children as $child)
                                                    @if(session('website_language') == "en")
                                                        <li><a href="index.html">{{ $child->name }}</a></li>
                                                    @else
                                                        <li><a href="index.html">{{ $child->vi_name }}</a></li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </nav>
                    </div>
                </div>
                <div class="hotline d-none d-lg-flex">
                    <img src="{{ asset('frontend/assets/imgs/theme/icons/icon-headphone.svg') }}" alt="hotline"/>
                    <p>1900 - 888<span>24/7 Support Center</span></p>
                </div>
                <div class="header-action-icon-2 d-block d-lg-none">
                    <div class="burger-icon burger-icon-white">
                        <span class="burger-icon-top"></span>
                        <span class="burger-icon-mid"></span>
                        <span class="burger-icon-bottom"></span>
                    </div>
                </div>
                <div class="header-action-right d-block d-lg-none">
                    <div class="header-action-2">
                        <div class="header-action-icon-2">
                            <a href="{{route('wishlist')}}">
                                <img alt="Nest" src="assets/imgs/theme/icons/icon-heart.svg"/>
                                <span class="pro-count white">4</span>
                            </a>
                        </div>
                        <div class="header-action-icon-2">
                            <a class="mini-cart-icon" href="#">
                                <img alt="Nest" src="assets/imgs/theme/icons/icon-cart.svg"/>
                                <span class="pro-count white">2</span>
                            </a>
                            <div class="cart-dropdown-wrap cart-dropdown-hm2">
                                <ul>
                                    <li>
                                        <div class="shopping-cart-img">
                                            <a href="shop-product-right.html"><img alt="Nest"
                                                                                   src="assets/imgs/shop/thumbnail-3.jpg"/></a>
                                        </div>
                                        <div class="shopping-cart-title">
                                            <h4><a href="shop-product-right.html">Plain Striola Shirts</a></h4>
                                            <h3><span>1 × </span>$800.00</h3>
                                        </div>
                                        <div class="shopping-cart-delete">
                                            <a href="#"><i class="fi-rs-cross-small"></i></a>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="shopping-cart-img">
                                            <a href="shop-product-right.html"><img alt="Nest"
                                                                                   src="assets/imgs/shop/thumbnail-4.jpg"/></a>
                                        </div>
                                        <div class="shopping-cart-title">
                                            <h4><a href="shop-product-right.html">Macbook Pro 2022</a></h4>
                                            <h3><span>1 × </span>$3500.00</h3>
                                        </div>
                                        <div class="shopping-cart-delete">
                                            <a href="#"><i class="fi-rs-cross-small"></i></a>
                                        </div>
                                    </li>
                                </ul>
                                <div class="shopping-cart-footer">
                                    <div class="shopping-cart-total">
                                        <h4>Total <span>$383.00</span></h4>
                                    </div>
                                    <div class="shopping-cart-button">
                                        <a href="shop-cart.html">View cart</a>
                                        <a href="shop-checkout.html">Checkout</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<div class="mobile-header-active mobile-header-wrapper-style">
    <div class="mobile-header-wrapper-inner">
        <div class="mobile-header-top">
            <div class="mobile-header-logo">
                <a href="index.html"><img src="assets/imgs/theme/logo.svg" alt="logo"/></a>
            </div>
            <div class="mobile-menu-close close-style-wrap close-style-position-inherit">
                <button class="close-style search-close">
                    <i class="icon-top"></i>
                    <i class="icon-bottom"></i>
                </button>
            </div>
        </div>
        <div class="mobile-header-content-area">
            <div class="mobile-search search-style-3 mobile-header-border">
                <form action="#">
                    <input type="text" placeholder="Search for items…"/>
                    <button type="submit"><i class="fi-rs-search"></i></button>
                </form>
            </div>
            <div class="mobile-menu-wrap mobile-header-border">
                <!-- mobile menu start -->
                <nav>
                    <ul class="mobile-menu font-heading">
                        <li class="menu-item-has-children">
                            <a href="index.html">Home</a>
                            <ul class="dropdown">
                                <li><a href="index.html">Home 1</a></li>
                                <li><a href="index-2.html">Home 2</a></li>
                                <li><a href="index-3.html">Home 3</a></li>
                                <li><a href="index-4.html">Home 4</a></li>
                                <li><a href="index-5.html">Home 5</a></li>
                                <li><a href="index-6.html">Home 6</a></li>
                            </ul>
                        </li>
                        <li class="menu-item-has-children">
                            <a href="shop-grid-right.html">shop</a>
                            <ul class="dropdown">
                                <li><a href="shop-grid-right.html">Shop Grid – Right Sidebar</a></li>
                                <li><a href="shop-grid-left.html">Shop Grid – Left Sidebar</a></li>
                                <li><a href="shop-list-right.html">Shop List – Right Sidebar</a></li>
                                <li><a href="shop-list-left.html">Shop List – Left Sidebar</a></li>
                                <li><a href="shop-fullwidth.html">Shop - Wide</a></li>
                                <li class="menu-item-has-children">
                                    <a href="#">Single Product</a>
                                    <ul class="dropdown">
                                        <li><a href="shop-product-right.html">Product – Right Sidebar</a></li>
                                        <li><a href="shop-product-left.html">Product – Left Sidebar</a></li>
                                        <li><a href="shop-product-full.html">Product – No sidebar</a></li>
                                        <li><a href="shop-product-vendor.html">Product – Vendor Infor</a></li>
                                    </ul>
                                </li>
                                <li><a href="shop-filter.html">Shop – Filter</a></li>
                                <li><a href="{{route('wishlist')}}">Shop – Wishlist</a></li>
                                <li><a href="shop-cart.html">Shop – Cart</a></li>
                                <li><a href="shop-checkout.html">Shop – Checkout</a></li>
                                <li><a href="shop-compare.html">Shop – Compare</a></li>
                                <li class="menu-item-has-children">
                                    <a href="#">Shop Invoice</a>
                                    <ul class="dropdown">
                                        <li><a href="shop-invoice-1.html">Shop Invoice 1</a></li>
                                        <li><a href="shop-invoice-2.html">Shop Invoice 2</a></li>
                                        <li><a href="shop-invoice-3.html">Shop Invoice 3</a></li>
                                        <li><a href="shop-invoice-4.html">Shop Invoice 4</a></li>
                                        <li><a href="shop-invoice-5.html">Shop Invoice 5</a></li>
                                        <li><a href="shop-invoice-6.html">Shop Invoice 6</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li class="menu-item-has-children">
                            <a href="#">Vendors</a>
                            <ul class="dropdown">
                                <li><a href="vendors-grid.html">Vendors Grid</a></li>
                                <li><a href="vendors-list.html">Vendors List</a></li>
                                <li><a href="vendor-details-1.html">Vendor Details 01</a></li>
                                <li><a href="vendor-details-2.html">Vendor Details 02</a></li>
                                <li><a href="vendor-dashboard.html">Vendor Dashboard</a></li>
                                <li><a href="vendor-guide.html">Vendor Guide</a></li>
                            </ul>
                        </li>
                        <li class="menu-item-has-children">
                            <a href="#">Mega menu</a>
                            <ul class="dropdown">
                                <li class="menu-item-has-children">
                                    <a href="#">Women's Fashion</a>
                                    <ul class="dropdown">
                                        <li><a href="shop-product-right.html">Dresses</a></li>
                                        <li><a href="shop-product-right.html">Blouses & Shirts</a></li>
                                        <li><a href="shop-product-right.html">Hoodies & Sweatshirts</a></li>
                                        <li><a href="shop-product-right.html">Women's Sets</a></li>
                                    </ul>
                                </li>
                                <li class="menu-item-has-children">
                                    <a href="#">Men's Fashion</a>
                                    <ul class="dropdown">
                                        <li><a href="shop-product-right.html">Jackets</a></li>
                                        <li><a href="shop-product-right.html">Casual Faux Leather</a></li>
                                        <li><a href="shop-product-right.html">Genuine Leather</a></li>
                                    </ul>
                                </li>
                                <li class="menu-item-has-children">
                                    <a href="#">Technology</a>
                                    <ul class="dropdown">
                                        <li><a href="shop-product-right.html">Gaming Laptops</a></li>
                                        <li><a href="shop-product-right.html">Ultraslim Laptops</a></li>
                                        <li><a href="shop-product-right.html">Tablets</a></li>
                                        <li><a href="shop-product-right.html">Laptop Accessories</a></li>
                                        <li><a href="shop-product-right.html">Tablet Accessories</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li class="menu-item-has-children">
                            <a href="blog-category-fullwidth.html">Blog</a>
                            <ul class="dropdown">
                                <li><a href="blog-category-grid.html">Blog Category Grid</a></li>
                                <li><a href="blog-category-list.html">Blog Category List</a></li>
                                <li><a href="blog-category-big.html">Blog Category Big</a></li>
                                <li><a href="blog-category-fullwidth.html">Blog Category Wide</a></li>
                                <li class="menu-item-has-children">
                                    <a href="#">Single Product Layout</a>
                                    <ul class="dropdown">
                                        <li><a href="blog-post-left.html">Left Sidebar</a></li>
                                        <li><a href="blog-post-right.html">Right Sidebar</a></li>
                                        <li><a href="blog-post-fullwidth.html">No Sidebar</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li class="menu-item-has-children">
                            <a href="#">Pages</a>
                            <ul class="dropdown">
                                <li><a href="page-about.html">About Us</a></li>
                                <li><a href="{{ route('contact') }}">Contact</a></li>
                                <li><a href="page-account.html">My Account</a></li>
                                <li><a href="page-login.html">Login</a></li>
                                <li><a href="page-register.html">Register</a></li>
                                <li><a href="page-forgot-password.html">Forgot password</a></li>
                                <li><a href="page-reset-password.html">Reset password</a></li>
                                <li><a href="page-purchase-guide.html">Purchase Guide</a></li>
                                <li><a href="page-privacy-policy.html">Privacy Policy</a></li>
                                <li><a href="page-terms.html">Terms of Service</a></li>
                                <li><a href="page-404.html">404 Page</a></li>
                            </ul>
                        </li>
                        <li class="menu-item-has-children">
                            <a href="#">Language</a>
                            <ul class="dropdown">
                                <li><a href="#">English</a></li>
                                <li><a href="#">French</a></li>
                                <li><a href="#">German</a></li>
                                <li><a href="#">Spanish</a></li>
                            </ul>
                        </li>
                    </ul>
                </nav>
                <!-- mobile menu end -->
            </div>
            <div class="mobile-header-info-wrap">
                <div class="single-mobile-header-info">
                    <a href="page-contact.html"><i class="fi-rs-marker"></i> Our location </a>
                </div>
                <div class="single-mobile-header-info">
                    <a href="page-login.html"><i class="fi-rs-user"></i>Log In / Sign Up </a>
                </div>
                <div class="single-mobile-header-info">
                    <a href="#"><i class="fi-rs-headphones"></i>(+01) - 2345 - 6789 </a>
                </div>
            </div>
            <div class="mobile-social-icon mb-50">
                <h6 class="mb-15">Follow Us</h6>
                <a href="#"><img src="assets/imgs/theme/icons/icon-facebook-white.svg" alt=""/></a>
                <a href="#"><img src="assets/imgs/theme/icons/icon-twitter-white.svg" alt=""/></a>
                <a href="#"><img src="assets/imgs/theme/icons/icon-instagram-white.svg" alt=""/></a>
                <a href="#"><img src="assets/imgs/theme/icons/icon-pinterest-white.svg" alt=""/></a>
                <a href="#"><img src="assets/imgs/theme/icons/icon-youtube-white.svg" alt=""/></a>
            </div>
            <div class="site-copyright">Copyright 2022 © Nest. All rights reserved. Powered by AliThemes.</div>
        </div>
    </div>
</div>
