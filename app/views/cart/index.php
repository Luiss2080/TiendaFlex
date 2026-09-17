<!-- Start Cart -->
<div class="container py-5">
    <h1 class="h2 mb-4">Carrito de Compras</h1>

    <?php if (empty($items)): ?>
        <div class="alert alert-info">
            Tu carrito está vacío. <a href="<?php echo View::url('shop'); ?>">Ir a la tienda</a>.
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th class="text-center">Precio</th>
                        <th class="text-center">Cantidad</th>
                        <th class="text-end">Subtotal</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                        <tr data-product-id="<?php echo (int)$item['product_id']; ?>">
                            <td><?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td class="text-center">$<?php echo number_format($item['price'], 2); ?></td>
                            <td class="text-center"><?php echo (int)$item['quantity']; ?></td>
                            <td class="text-end">$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                            <td class="text-end">
                                <button type="button" class="btn btn-sm btn-outline-danger btn-remove-from-cart" data-product-id="<?php echo (int)$item['product_id']; ?>">
                                    Quitar
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" class="text-end">Total</th>
                        <th class="text-end">$<?php echo number_format($total, 2); ?></th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    <?php endif; ?>
</div>
<!-- End Cart -->

<script>
document.querySelectorAll('.btn-remove-from-cart').forEach(function (btn) {
    btn.addEventListener('click', function () {
        fetch('/cart/remove', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ product_id: this.dataset.productId })
        })
        .then(function (response) { return response.json(); })
        .then(function () { window.location.reload(); });
    });
});
</script>
