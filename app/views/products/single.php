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

<!-- Open Content -->
<section class="bg-light">
    <div class="container pb-5">
        <div class="row">
            <div class="col-lg-5 mt-5">
                <div class="card mb-3">
                    <img class="card-img img-fluid" src="<?php echo View::asset('img/'); ?><?php echo $product['image'] ?? 'product_single_01.jpg'; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" id="product-detail">
                </div>
                <div class="row">
                    <!--Start Controls-->
                    <div class="col-1 align-self-center">
                        <a href="#multi-item-example" role="button" data-bs-slide="prev">
                            <i class="text-dark fas fa-chevron-left"></i>
                            <span class="sr-only">Previous</span>
                        </a>
                    </div>
                    <!--End Controls-->
                    <!--Start Carousel Wrapper-->
                    <div id="multi-item-example" class="col-10 carousel slide carousel-multi-item" data-bs-ride="carousel">
                        <!--Start Slides-->
                        <div class="carousel-inner product-links-wap" role="listbox">

                            <!--Start First slide-->
                            <div class="carousel-item active">
                                <div class="row">
                                    <div class="col-4">
                                        <a href="#">
                                            <img class="card-img img-fluid" src="<?php echo View::asset('img/'); ?><?php echo $product['image'] ?? 'product_single_01.jpg'; ?>" alt="Product Image">
                                        </a>
                                    </div>
                                    <div class="col-4">
                                        <a href="#">
                                            <img class="card-img img-fluid" src="<?php echo View::asset('img/'); ?>product_single_02.jpg" alt="Product Image">
                                        </a>
                                    </div>
                                    <div class="col-4">
                                        <a href="#">
                                            <img class="card-img img-fluid" src="<?php echo View::asset('img/'); ?>product_single_03.jpg" alt="Product Image">
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <!--End First slide-->

                            <!--Start Second slide-->
                            <div class="carousel-item">
                                <div class="row">
                                    <div class="col-4">
                                        <a href="#">
                                            <img class="card-img img-fluid" src="<?php echo View::asset('img/'); ?>product_single_04.jpg" alt="Product Image">
                                        </a>
                                    </div>
                                    <div class="col-4">
                                        <a href="#">
                                            <img class="card-img img-fluid" src="<?php echo View::asset('img/'); ?>product_single_05.jpg" alt="Product Image">
                                        </a>
                                    </div>
                                    <div class="col-4">
                                        <a href="#">
                                            <img class="card-img img-fluid" src="<?php echo View::asset('img/'); ?>product_single_06.jpg" alt="Product Image">
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <!--End Second slide-->

                            <!--Start Third slide-->
                            <div class="carousel-item">
                                <div class="row">
                                    <div class="col-4">
                                        <a href="#">
                                            <img class="card-img img-fluid" src="<?php echo View::asset('img/'); ?>product_single_07.jpg" alt="Product Image">
                                        </a>
                                    </div>
                                    <div class="col-4">
                                        <a href="#">
                                            <img class="card-img img-fluid" src="<?php echo View::asset('img/'); ?>product_single_08.jpg" alt="Product Image">
                                        </a>
                                    </div>
                                    <div class="col-4">
                                        <a href="#">
                                            <img class="card-img img-fluid" src="<?php echo View::asset('img/'); ?>product_single_09.jpg" alt="Product Image">
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <!--End Third slide-->
                        </div>
                        <!--End Slides-->
                    </div>
                    <!--End Carousel Wrapper-->
                    <!--Start Controls-->
                    <div class="col-1 align-self-center">
                        <a href="#multi-item-example" role="button" data-bs-slide="next">
                            <i class="text-dark fas fa-chevron-right"></i>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                    <!--End Controls-->
                </div>
            </div>
            <!-- col end -->
            <div class="col-lg-7 mt-5">
                <div class="card">
                    <div class="card-body">
                        <h1 class="h2"><?php echo htmlspecialchars($product['name']); ?></h1>
                        <p class="h3 py-2">
                            <?php if (!empty($product['sale_price'])): ?>
                                <span class="text-muted text-decoration-line-through">$<?php echo number_format($product['price'], 2); ?></span>
                                <span class="text-success">$<?php echo number_format($product['sale_price'], 2); ?></span>
                            <?php else: ?>
                                $<?php echo number_format($product['price'], 2); ?>
                            <?php endif; ?>
                        </p>
                        <ul class="list-inline">
                            <li class="list-inline-item">
                                <h6>Brand:</h6>
                            </li>
                            <li class="list-inline-item">
                                <p class="text-muted"><strong><?php echo htmlspecialchars($product['category_name'] ?? 'Fashion'); ?></strong></p>
                            </li>
                        </ul>

                        <h6>Description:</h6>
                        <p><?php echo htmlspecialchars($product['description']); ?></p>
                        <ul class="list-inline">
                            <li class="list-inline-item">
                                <h6>Avaliable Size :</h6>
                            </li>
                            <li class="list-inline-item">
                                <p class="text-muted"><strong>S / M / L / XL</strong></p>
                            </li>
                        </ul>

                        <h6>Specification:</h6>
                        <ul class="list-unstyled pb-3">
                            <li>Lorem ipsum dolor sit</li>
                            <li>Amet, consectetur</li>
                            <li>Adipiscing elit,set</li>
                            <li>Duis aute irure</li>
                            <li>Ut enim ad minim</li>
                            <li>Dolore magna aliqua</li>
                            <li>Excepteur sint</li>
                        </ul>

                        <form action="/cart/add" method="POST" onsubmit="addToCartFromForm(event)">
                            <input type="hidden" name="csrf_token" value="<?php echo Session::getCsrf(); ?>">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                            
                            <div class="row">
                                <div class="col-auto">
                                    <ul class="list-inline pb-3">
                                        <li class="list-inline-item">Size :
                                            <input type="hidden" name="product-size" id="product-size" value="S">
                                        </li>
                                        <li class="list-inline-item"><span class="btn btn-success btn-size">S</span></li>
                                        <li class="list-inline-item"><span class="btn btn-success btn-size">M</span></li>
                                        <li class="list-inline-item"><span class="btn btn-success btn-size">L</span></li>
                                        <li class="list-inline-item"><span class="btn btn-success btn-size">XL</span></li>
                                    </ul>
                                </div>
                                <div class="col-auto">
                                    <ul class="list-inline pb-3">
                                        <li class="list-inline-item text-right">
                                            Quantity
                                            <input type="hidden" name="product-quanity" id="product-quanity" value="1">
                                        </li>
                                        <li class="list-inline-item"><span class="btn btn-success" id="btn-minus">-</span></li>
                                        <li class="list-inline-item"><span class="badge bg-secondary" id="var-value">1</span></li>
                                        <li class="list-inline-item"><span class="btn btn-success" id="btn-plus">+</span></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="row pb-3">
                                <div class="col d-grid">
                                    <button type="submit" class="btn btn-success btn-lg" name="submit" value="buy">Buy</button>
                                </div>
                                <div class="col d-grid">
                                    <button type="submit" class="btn btn-success btn-lg" name="submit" value="addtocard">Add To Cart</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Close Content -->

<!-- Start Article -->
<section class="py-5">
    <div class="container">
        <div class="row text-left p-2 pb-3">
            <h4>Related Products</h4>
        </div>

        <!--Start Carousel Wrapper-->
        <div id="carousel-related-product">
            <div class="p-2 pb-3">
                <div class="row">
                    <?php if (!empty($related_products)): ?>
                        <?php foreach (array_slice($related_products, 0, 4) as $relatedProduct): ?>
                            <div class="col-3">
                                <div class="card mb-4 product-wap rounded-0">
                                    <div class="card rounded-0">
                                        <img class="card-img rounded-0 img-fluid" src="<?php echo View::asset('img/'); ?><?php echo $relatedProduct['image'] ?? 'shop_01.jpg'; ?>">
                                        <div class="card-img-overlay rounded-0 product-overlay d-flex align-items-center justify-content-center">
                                            <ul class="list-unstyled">
                                                <li><a class="btn btn-success text-white" href="/shop/product/<?php echo $relatedProduct['id']; ?>"><i class="far fa-heart"></i></a></li>
                                                <li><a class="btn btn-success text-white mt-2" href="/shop/product/<?php echo $relatedProduct['id']; ?>"><i class="far fa-eye"></i></a></li>
                                                <li><a class="btn btn-success text-white mt-2" href="#" onclick="addToCart(<?php echo $relatedProduct['id']; ?>)"><i class="fas fa-cart-plus"></i></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <a href="/shop/product/<?php echo $relatedProduct['id']; ?>" class="h3 text-decoration-none"><?php echo htmlspecialchars($relatedProduct['name']); ?></a>
                                        <ul class="w-100 list-unstyled d-flex justify-content-between mb-0">
                                            <li class="pt-2">
                                                <span class="product-color-dot color-dot-red float-left rounded-circle ml-1"></span>
                                                <span class="product-color-dot color-dot-blue float-left rounded-circle ml-1"></span>
                                                <span class="product-color-dot color-dot-black float-left rounded-circle ml-1"></span>
                                                <span class="product-color-dot color-dot-light float-left rounded-circle ml-1"></span>
                                                <span class="product-color-dot color-dot-green float-left rounded-circle ml-1"></span>
                                            </li>
                                        </ul>
                                        <p class="text-center mb-0">$<?php echo number_format($relatedProduct['price'], 2); ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <!-- Productos por defecto si no hay relacionados -->
                        <div class="col-3">
                            <div class="card mb-4 product-wap rounded-0">
                                <div class="card rounded-0">
                                    <img class="card-img rounded-0 img-fluid" src="<?php echo View::asset('img/'); ?>shop_04.jpg">
                                    <div class="card-img-overlay rounded-0 product-overlay d-flex align-items-center justify-content-center">
                                        <ul class="list-unstyled">
                                            <li><a class="btn btn-success text-white" href="/shop/product/4"><i class="far fa-heart"></i></a></li>
                                            <li><a class="btn btn-success text-white mt-2" href="/shop/product/4"><i class="far fa-eye"></i></a></li>
                                            <li><a class="btn btn-success text-white mt-2" href="#" onclick="addToCart(4)"><i class="fas fa-cart-plus"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <a href="/shop/product/4" class="h3 text-decoration-none">Lovely Proxy</a>
                                    <p class="text-center mb-0">$75.00</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="card mb-4 product-wap rounded-0">
                                <div class="card rounded-0">
                                    <img class="card-img rounded-0 img-fluid" src="<?php echo View::asset('img/'); ?>shop_05.jpg">
                                    <div class="card-img-overlay rounded-0 product-overlay d-flex align-items-center justify-content-center">
                                        <ul class="list-unstyled">
                                            <li><a class="btn btn-success text-white" href="/shop/product/5"><i class="far fa-heart"></i></a></li>
                                            <li><a class="btn btn-success text-white mt-2" href="/shop/product/5"><i class="far fa-eye"></i></a></li>
                                            <li><a class="btn btn-success text-white mt-2" href="#" onclick="addToCart(5)"><i class="fas fa-cart-plus"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <a href="/shop/product/5" class="h3 text-decoration-none">Galaxy Shoes</a>
                                    <p class="text-center mb-0">$450.00</p>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Article -->

<script>
$(document).ready(function() {
    // Manejo de tallas
    $('.btn-size').click(function() {
        var size = $(this).text();
        $('.btn-size').removeClass('btn-secondary').addClass('btn-success');
        $(this).removeClass('btn-success').addClass('btn-secondary');
        $('#product-size').val(size);
    });

    // Manejo de cantidad
    $('#btn-minus').click(function() {
        var currentVal = parseInt($('#var-value').text());
        if (currentVal > 1) {
            currentVal--;
            $('#var-value').text(currentVal);
            $('#product-quanity').val(currentVal);
        }
    });

    $('#btn-plus').click(function() {
        var currentVal = parseInt($('#var-value').text());
        currentVal++;
        $('#var-value').text(currentVal);
        $('#product-quanity').val(currentVal);
    });
});

function addToCartFromForm(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    const productId = formData.get('product_id');
    const quantity = formData.get('product-quanity');
    const size = formData.get('product-size');
    
    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: quantity,
            size: size
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Product added to cart successfully!');
            // Actualizar contador del carrito si existe
            const cartCounter = document.querySelector('.cart-counter');
            if (cartCounter) {
                cartCounter.textContent = data.cart_count;
            }
        } else {
            alert('Error adding product to cart');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error adding product to cart');
    });
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