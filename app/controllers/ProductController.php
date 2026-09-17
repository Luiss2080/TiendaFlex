<?php

class ProductController extends Controller
{
    private $productModel;
    private $categoryModel;

    public function __construct()
    {
        parent::__construct();
        $this->productModel = new Product();
        $this->categoryModel = new Category();
    }

    public function index()
    {
        // Parámetros de filtro
        $page = (int)($_GET['page'] ?? 1);
        $category = $_GET['category'] ?? null;
        $sort = $_GET['sort'] ?? 'featured';
        $search = $_GET['search'] ?? null;
        
        // Usar datos de ejemplo temporalmente
        $filters = [
            'page' => $page,
            'per_page' => 9,
            'category' => $category,
            'search' => $search,
            'sort' => $sort
        ];
        
        $productsResult = $this->productModel->getSampleProducts($filters);
        $categories = $this->productModel->getSampleCategories();

        $data = [
            'title' => 'Shop - Zay Shop',
            'meta_description' => 'Shop the latest products at Zay Shop',
            'products' => $productsResult['data'],
            'categories' => $categories,
            'pagination' => [
                'current_page' => $page,
                'per_page' => 9,
                'total' => $productsResult['total'],
                'has_more' => $productsResult['has_more']
            ],
            'filters' => [
                'category' => $category,
                'sort' => $sort,
                'search' => $search
            ]
        ];

        return $this->renderWithLayout('products/shop', $data);
    }

    public function show($id)
    {
        if (!$id) {
            return $this->redirect('/shop');
        }

        // Obtener producto de los datos de ejemplo
        $sampleProducts = $this->productModel->getSampleProducts([]);
        $product = null;
        
        foreach ($sampleProducts['data'] as $sampleProduct) {
            if ($sampleProduct['id'] == $id) {
                $product = $sampleProduct;
                break;
            }
        }
        
        if (!$product) {
            $this->response->setStatusCode(404);
            $data = ['title' => '404 - Product Not Found'];
            return $this->renderWithLayout('errors/404', $data);
        }

        // Productos relacionados (de la misma categoría)
        $relatedProducts = [];
        foreach ($sampleProducts['data'] as $sampleProduct) {
            if ($sampleProduct['category_id'] == $product['category_id'] && $sampleProduct['id'] != $id) {
                $relatedProducts[] = $sampleProduct;
            }
        }

        $data = [
            'title' => $product['name'] . ' - Zay Shop',
            'meta_description' => substr($product['description'], 0, 160),
            'product' => $product,
            'related_products' => $relatedProducts
        ];

        return $this->renderWithLayout('products/single', $data);
    }

    /**
     * Listado de productos filtrados por categoría: /shop/category/{id}
     *
     * Nota: mientras el catálogo siga usando datos de ejemplo en memoria
     * (ver Product::getSampleProducts), este método filtra sobre esos
     * mismos datos en lugar de golpear la base de datos real, igual que
     * index(). Esto evita depender de una conexión MySQL para navegar
     * el catálogo de muestra.
     */
    public function category($id)
    {
        $categories = $this->productModel->getSampleCategories();
        $currentCategory = null;
        foreach ($categories as $cat) {
            if ((string)$cat['id'] === (string)$id) {
                $currentCategory = $cat;
                break;
            }
        }

        if (!$currentCategory) {
            $this->response->setStatusCode(404);
            $data = ['title' => '404 - Category Not Found'];
            return $this->renderWithLayout('errors/404', $data);
        }

        $page = (int)($_GET['page'] ?? 1);
        $sort = $_GET['sort'] ?? 'featured';

        $filters = [
            'page' => $page,
            'per_page' => 9,
            'category' => $id,
            'sort' => $sort
        ];

        $productsResult = $this->productModel->getSampleProducts($filters);

        $data = [
            'title' => $currentCategory['name'] . ' - Zay Shop',
            'meta_description' => 'Shop ' . $currentCategory['name'] . ' at Zay Shop',
            'products' => $productsResult['data'],
            'categories' => $categories,
            'pagination' => [
                'current_page' => $page,
                'per_page' => 9,
                'total' => $productsResult['total'],
                'has_more' => $productsResult['has_more']
            ],
            'filters' => [
                'category' => $id,
                'sort' => $sort,
                'search' => null
            ]
        ];

        return $this->renderWithLayout('products/shop', $data);
    }

    /**
     * Búsqueda de productos: GET /api/search?q=termino
     *
     * Devuelve JSON. La búsqueda se hace en memoria sobre los datos de
     * ejemplo (stripos), por lo que no hay concatenación de SQL con
     * entrada del usuario en ningún punto de este método.
     */
    public function search()
    {
        $query = trim((string)($_GET['q'] ?? $_GET['search'] ?? ''));

        $result = $this->productModel->getSampleProducts([
            'search' => $query,
            'per_page' => 50
        ]);

        return $this->json([
            'query' => $query,
            'total' => $result['total'],
            'results' => $result['data']
        ]);
    }
}