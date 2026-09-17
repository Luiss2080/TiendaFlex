<!-- Modal Search -->
<div class="modal fade bg-white" id="templatemo_search" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="w-100 pt-1 mb-5 text-right">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="/api/search" method="get" class="modal-content modal-body border-0 p-0">
            <div class="input-group mb-2">
                <input type="text" class="form-control" id="inputModalSearch" name="q" placeholder="Search ...">
                <button class="input-group-text bg-success text-light" type="submit">
                    <i class="fa fa-fw fa-search"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Start Content -->
<div class="container py-5">
    <div class="row">
        <!-- Start Sidebar -->
        <div class="col-lg-3">
            <!-- Categories -->
            <h1 class="h2 pb-4">Categories</h1>
            <ul class="list-unstyled templatemo-accordion">
                <li class="pb-3">
                    <a class="collapsed d-flex justify-content-between h3 text-decoration-none" href="/shop">
                        All Products
                        <i class="fa fa-fw fa-chevron-circle-down mt-1"></i>
                    </a>
                </li>
                <?php if (!empty($categories)): ?>
                    <?php foreach ($categories as $cat): ?>
                        <li class="pb-3">
                            <a class="collapsed d-flex justify-content-between h3 text-decoration-none" href="/shop/category/<?php echo $cat['id']; ?>">
                                <?php echo htmlspecialchars($cat['name']); ?>
                                <i class="fa fa-fw fa-chevron-circle-down mt-1"></i>
                            </a>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
            
            <!-- Size Filter -->
            <h2 class="h5 mt-4 mb-3">Size</h2>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="S" id="size-s">
                <label class="form-check-label" for="size-s">S</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="M" id="size-m">
                <label class="form-check-label" for="size-m">M</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="L" id="size-l">
                <label class="form-check-label" for="size-l">L</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="XL" id="size-xl">
                <label class="form-check-label" for="size-xl">XL</label>
            </div>
        </div>
        <!-- End Sidebar -->

        <!-- Start Products -->
        <div class="col-lg-9">
            <div class="row">
                <div class="col-md-6">
                    <h1 class="h2">Shop</h1>
                </div>
                <div class="col-md-6 pb-4">
                    <div class="d-flex">
                        <select class="form-control" name="sort" onchange="applySorting(this.value)">
                            <option value="featured" <?php echo ($filters['sort'] ?? '') === 'featured' ? 'selected' : ''; ?>>Featured</option>
                            <option value="name_asc" <?php echo ($filters['sort'] ?? '') === 'name_asc' ? 'selected' : ''; ?>>A to Z</option>
                            <option value="name_desc" <?php echo ($filters['sort'] ?? '') === 'name_desc' ? 'selected' : ''; ?>>Z to A</option>
                            <option value="price_asc" <?php echo ($filters['sort'] ?? '') === 'price_asc' ? 'selected' : ''; ?>>Price Low to High</option>
                            <option value="price_desc" <?php echo ($filters['sort'] ?? '') === 'price_desc' ? 'selected' : ''; ?>>Price High to Low</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <?php if (!empty($products)): ?>
                    <?php foreach ($products as $product): ?>
                        <div class="col-md-4">
                            <div class="card mb-4 product-wap rounded-0">
                                <div class="card rounded-0">
                                    <img class="card-img rounded-0 img-fluid" src="<?php echo View::asset('img/'); ?><?php echo $product['image'] ?? 'shop_01.jpg'; ?>">
                                    <div class="card-img-overlay rounded-0 product-overlay d-flex align-items-center justify-content-center">
                                        <ul class="list-unstyled">
                                            <li><a class="btn btn-success text-white" href="/shop/product/<?php echo $product['id']; ?>"><i class="far fa-heart"></i></a></li>
                                            <li><a class="btn btn-success text-white mt-2" href="/shop/product/<?php echo $product['id']; ?>"><i class="far fa-eye"></i></a></li>
                                            <li><a class="btn btn-success text-white mt-2" href="#" onclick="addToCart(<?php echo $product['id']; ?>)"><i class="fas fa-cart-plus"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <a href="/shop/product/<?php echo $product['id']; ?>" class="h3 text-decoration-none"><?php echo htmlspecialchars($product['name']); ?></a>
                                    <ul class="w-100 list-unstyled d-flex justify-content-between mb-0">
                                        <li><?php echo htmlspecialchars($product['size'] ?? 'M'); ?></li>
                                        <li class="pt-2">
                                            <span class="product-color-dot color-dot-red float-left rounded-circle ml-1"></span>
                                            <span class="product-color-dot color-dot-blue float-left rounded-circle ml-1"></span>
                                            <span class="product-color-dot color-dot-black float-left rounded-circle ml-1"></span>
                                            <span class="product-color-dot color-dot-light float-left rounded-circle ml-1"></span>
                                            <span class="product-color-dot color-dot-green float-left rounded-circle ml-1"></span>
                                        </li>
                                    </ul>
                                    <ul class="list-unstyled d-flex justify-content-center mb-1">
                                        <li>
                                            <i class="text-warning fa fa-star"></i>
                                            <i class="text-warning fa fa-star"></i>
                                            <i class="text-warning fa fa-star"></i>
                                            <i class="text-muted fa fa-star"></i>
                                            <i class="text-muted fa fa-star"></i>
                                        </li>
                                    </ul>
                                    <p class="text-center mb-0">$<?php echo number_format($product['price'], 2); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="alert alert-info text-center">
                            <h3>No products found</h3>
                            <p>Try adjusting your search or filter criteria.</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Pagination -->
            <?php if (!empty($pagination) && $pagination['total'] > $pagination['per_page']): ?>
                <div class="row">
                    <ul class="pagination pagination-lg justify-content-end">
                        <?php 
                        $currentPage = $pagination['current_page'];
                        $totalPages = ceil($pagination['total'] / $pagination['per_page']);
                        ?>
                        
                        <?php if ($currentPage > 1): ?>
                            <li class="page-item">
                                <a class="page-link active rounded-0 mr-3 shadow-sm border-top-0 border-left-0" href="?page=<?php echo $currentPage - 1; ?>" tabindex="-1">Previous</a>
                            </li>
                        <?php endif; ?>
                        
                        <?php for ($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++): ?>
                            <li class="page-item">
                                <a class="page-link rounded-0 mr-3 shadow-sm border-top-0 border-left-0 text-dark <?php echo $i === $currentPage ? 'active' : ''; ?>" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>
                        
                        <?php if ($currentPage < $totalPages): ?>
                            <li class="page-item">
                                <a class="page-link rounded-0 shadow-sm border-top-0 border-left-0" href="?page=<?php echo $currentPage + 1; ?>">Next</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
        <!-- End Products -->
    </div>
</div>
<!-- End Content -->

<!-- Start Brands -->
<section class="bg-light py-5">
    <div class="container my-4">
        <div class="row text-center py-3">
            <div class="col-lg-6 m-auto">
                <h1 class="h1">Our Brands</h1>
                <p>
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                    Lorem ipsum dolor sit amet.
                </p>
            </div>
            <div class="col-lg-9 m-auto tempaltemo-carousel">
                <div class="row d-flex flex-row">
                    <!--Controls-->
                    <div class="col-1 align-self-center">
                        <a class="h1" href="#multi-item-example" role="button" data-bs-slide="prev">
                            <i class="text-light fas fa-chevron-left"></i>
                        </a>
                    </div>
                    <!--End Controls-->

                    <!--Carousel Wrapper-->
                    <div class="col">
                        <div class="carousel slide carousel-multi-item pt-2 pt-md-0" id="multi-item-example" data-bs-ride="carousel">
                            <!--Slides-->
                            <div class="carousel-inner product-links-wap" role="listbox">
                                <!--First slide-->
                                <div class="carousel-item active">
                                    <div class="row">
                                        <div class="col-3 p-md-5">
                                            <a href="#"><img class="img-fluid brand-img" src="<?php echo View::asset('img/'); ?>brand_01.png" alt="Brand Logo"></a>
                                        </div>
                                        <div class="col-3 p-md-5">
                                            <a href="#"><img class="img-fluid brand-img" src="<?php echo View::asset('img/'); ?>brand_02.png" alt="Brand Logo"></a>
                                        </div>
                                        <div class="col-3 p-md-5">
                                            <a href="#"><img class="img-fluid brand-img" src="<?php echo View::asset('img/'); ?>brand_03.png" alt="Brand Logo"></a>
                                        </div>
                                        <div class="col-3 p-md-5">
                                            <a href="#"><img class="img-fluid brand-img" src="<?php echo View::asset('img/'); ?>brand_04.png" alt="Brand Logo"></a>
                                        </div>
                                    </div>
                                </div>
                                <!--End First slide-->
                            </div>
                            <!--End Slides-->
                        </div>
                    </div>
                    <!--End Carousel Wrapper-->

                    <!--Controls-->
                    <div class="col-1 align-self-center">
                        <a class="h1" href="#multi-item-example" role="button" data-bs-slide="next">
                            <i class="text-light fas fa-chevron-right"></i>
                        </a>
                    </div>
                    <!--End Controls-->
                </div>
            </div>
        </div>
    </div>
</section>
<!--End Brands-->

<script>
function applySorting(sortValue) {
    const url = new URL(window.location);
    url.searchParams.set('sort', sortValue);
    window.location = url;
}

function addToCart(productId) {
    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: 1
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update cart counter if exists
            const cartCounter = document.querySelector('.cart-counter');
            if (cartCounter) {
                cartCounter.textContent = data.cart_count;
            }
            
            // Show success message
            alert('Product added to cart successfully!');
        } else {
            alert('Error adding product to cart');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error adding product to cart');
    });
}
</script>