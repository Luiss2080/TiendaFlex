<?php

/**
 * Carrito de compras basado en sesión.
 *
 * Regla de seguridad central de este controlador: el precio de cada
 * artículo SIEMPRE se resuelve consultando el catálogo del lado del
 * servidor (Product::getSampleProducts) a partir únicamente del
 * product_id recibido. Nunca se lee ni se confía en un campo "price"
 * (o similar) que pudiera venir del cliente, precisamente para evitar
 * el clásico ataque de manipular el precio en el carrito modificando
 * un campo oculto del formulario o el payload de la petición AJAX.
 */
class CartController extends Controller
{
    private const SESSION_KEY = 'cart';
    private const MAX_QUANTITY_PER_ITEM = 100;

    private $productModel;

    public function __construct()
    {
        parent::__construct();
        $this->productModel = new Product();
    }

    public function index()
    {
        $cart = $this->getCartWithTotals();

        $data = [
            'title' => 'Carrito de Compras - Zay Shop',
            'items' => $cart['items'],
            'total' => $cart['total'],
            'count' => $cart['count']
        ];

        return $this->renderWithLayout('cart/index', $data);
    }

    public function add()
    {
        if (!$this->request->isPost()) {
            return $this->json(['success' => false, 'error' => 'Método no permitido'], 405);
        }

        try {
            $this->validateCsrf();
        } catch (Exception $e) {
            return $this->json(['success' => false, 'error' => 'Token CSRF inválido'], 403);
        }

        $productId = $this->getInput('product_id');
        $product = $this->findProductOrFail($productId);

        if (!$product) {
            return $this->json(['success' => false, 'error' => 'Producto no encontrado'], 404);
        }

        $quantity = $this->sanitizeQuantity($this->getInput('quantity', 1));

        $cart = Session::get(self::SESSION_KEY, []);
        $key = (string)$product['id'];

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] = $this->sanitizeQuantity($cart[$key]['quantity'] + $quantity);
            // El precio se re-sincroniza siempre con el catálogo actual,
            // por si cambió desde que el artículo se agregó por primera vez.
            $cart[$key]['price'] = $this->resolveServerPrice($product);
        } else {
            $cart[$key] = [
                'product_id' => (int)$product['id'],
                'name' => $product['name'],
                'price' => $this->resolveServerPrice($product),
                'quantity' => $quantity
            ];
        }

        Session::set(self::SESSION_KEY, $cart);

        $totals = $this->getCartWithTotals();

        return $this->json([
            'success' => true,
            'cart_count' => $totals['count'],
            'cart_total' => $totals['total']
        ]);
    }

    public function update()
    {
        if (!$this->request->isPost()) {
            return $this->json(['success' => false, 'error' => 'Método no permitido'], 405);
        }

        try {
            $this->validateCsrf();
        } catch (Exception $e) {
            return $this->json(['success' => false, 'error' => 'Token CSRF inválido'], 403);
        }

        $productId = $this->getInput('product_id');
        $key = (string)(int)$productId;

        $cart = Session::get(self::SESSION_KEY, []);

        if (!isset($cart[$key])) {
            return $this->json(['success' => false, 'error' => 'El producto no está en el carrito'], 404);
        }

        $quantity = (int)$this->getInput('quantity', 0);

        if ($quantity <= 0) {
            unset($cart[$key]);
        } else {
            $cart[$key]['quantity'] = $this->sanitizeQuantity($quantity);

            // Re-verificar el producto sigue existiendo y refrescar su
            // precio desde el catálogo del servidor antes de guardar.
            $product = $this->findProductOrFail($productId);
            if (!$product) {
                unset($cart[$key]);
            } else {
                $cart[$key]['price'] = $this->resolveServerPrice($product);
            }
        }

        Session::set(self::SESSION_KEY, $cart);

        $totals = $this->getCartWithTotals();

        return $this->json([
            'success' => true,
            'cart_count' => $totals['count'],
            'cart_total' => $totals['total']
        ]);
    }

    public function remove()
    {
        if (!$this->request->isPost()) {
            return $this->json(['success' => false, 'error' => 'Método no permitido'], 405);
        }

        try {
            $this->validateCsrf();
        } catch (Exception $e) {
            return $this->json(['success' => false, 'error' => 'Token CSRF inválido'], 403);
        }

        $productId = $this->getInput('product_id');
        $key = (string)(int)$productId;

        $cart = Session::get(self::SESSION_KEY, []);
        unset($cart[$key]);
        Session::set(self::SESSION_KEY, $cart);

        $totals = $this->getCartWithTotals();

        return $this->json([
            'success' => true,
            'cart_count' => $totals['count'],
            'cart_total' => $totals['total']
        ]);
    }

    public function count()
    {
        $totals = $this->getCartWithTotals();
        return $this->json(['count' => $totals['count']]);
    }

    /**
     * Busca el producto real en el catálogo de ejemplo del servidor por
     * id. El id es el único dato del cliente que se usa para identificar
     * QUÉ producto es; todo lo demás (nombre, precio) se toma del
     * resultado de esta búsqueda, nunca de la petición.
     */
    private function findProductOrFail($productId): ?array
    {
        if (!is_numeric($productId)) {
            return null;
        }

        $sample = $this->productModel->getSampleProducts([]);
        $normalizedId = (string)(int)$productId;

        foreach ($sample['data'] as $product) {
            if ((string)$product['id'] === $normalizedId) {
                return $product;
            }
        }

        return null;
    }

    /**
     * Precio autoritativo de un producto: precio de oferta si existe,
     * si no el precio normal. Siempre calculado a partir del registro
     * del catálogo del servidor recibido como argumento, nunca de
     * entrada externa.
     */
    private function resolveServerPrice(array $product): float
    {
        return (float)($product['sale_price'] ?? $product['price']);
    }

    private function sanitizeQuantity($quantity): int
    {
        $quantity = (int)$quantity;

        if ($quantity < 1) {
            $quantity = 1;
        }

        if ($quantity > self::MAX_QUANTITY_PER_ITEM) {
            $quantity = self::MAX_QUANTITY_PER_ITEM;
        }

        return $quantity;
    }

    private function getCartWithTotals(): array
    {
        $cart = Session::get(self::SESSION_KEY, []);
        $items = array_values($cart);

        $total = 0.0;
        $count = 0;

        foreach ($items as $item) {
            $total += $item['price'] * $item['quantity'];
            $count += $item['quantity'];
        }

        // Mantener sincronizado el contador que ya usa el badge del
        // carrito en app/views/layouts/header.php.
        Session::set('cart_count', $count);

        return [
            'items' => $items,
            'total' => round($total, 2),
            'count' => $count
        ];
    }
}
