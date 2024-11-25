<?php
include 'db.php';
session_start();
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

$sort = $_POST['sort'] ?? 'price_asc';
$categories = $_POST['categories'] ?? '';
$price_min = $_POST['price_min'] ?? 0;
$price_max = $_POST['price_max'] ?? PHP_INT_MAX;

function build_product_select_query($user_id, &$bind_params) {
    $sql = "SELECT p.product_id, p.name, p.description, p.price, p.image";
    if ($user_id !== null) {
        $sql .= ", (SELECT COUNT(*) FROM wishlist w WHERE w.product_id = p.product_id AND w.user_id = ?) AS in_wishlist";
        $bind_params[] = $user_id; // Додаємо user_id у масив параметрів
    } else {
        $sql .= ", 0 AS in_wishlist";
    }
    $sql .= " FROM products p";
    return $sql;
}
$bind_params = [];
$sql = build_product_select_query($user_id, $bind_params);

// Додаємо фільтри, сортування тощо, як і раніше
$category_filters = [];
if (!empty($categories)) {
    $categories_array = explode(',', $categories);
    $categories_placeholders = implode(',', array_fill(0, count($categories_array), '?'));
    $sql .= " JOIN product_categories pc ON p.category_id = pc.category_id";
    $category_filters[] = "pc.name IN ($categories_placeholders)";
    foreach ($categories_array as $category) {
        $bind_params[] = $category;
    }
}

$category_filters[] = "p.price BETWEEN ? AND ?";
$bind_params[] = $price_min;
$bind_params[] = $price_max;

if (!empty($category_filters)) {
    $sql .= " WHERE " . implode(' AND ', $category_filters);
}

$sort_options = [
    'price_asc' => "ORDER BY p.price ASC",
    'price_desc' => "ORDER BY p.price DESC",
    'name_asc' => "ORDER BY p.name ASC",
    'name_desc' => "ORDER BY p.name DESC",
    'date_desc' => "ORDER BY p.product_id DESC"
];
$sql .= " " . ($sort_options[$sort] ?? "ORDER BY p.price ASC");

// Виконуємо запит
$stmt = $conn->prepare($sql);

// Підв'язуємо параметри
if (!empty($bind_params)) {
    $types = str_repeat('s', count($bind_params));
    $stmt->bind_param($types, ...$bind_params);
}

$stmt->execute();

$result = $stmt->get_result();

$products = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $old_price = $row['price'] * 1.25;
        $in_wishlist = $row['in_wishlist'] > 0;

        $product = [
            'product_id' => $row['product_id'],
            'name' => $row['name'],
            'description' => $row['description'],
            'price' => $row['price'],
            'image' => $row['image'],
            'old_price' => $old_price,
            'in_wishlist' => $in_wishlist
        ];

        $products[] = $product;
    }
}

foreach ($products as $product) {
    ?>
    <div class="good" style="width: 300px; height: 450px;">
        <a href="product.php?product_id=<?= $product['product_id'] ?>" class="cat_pr">
            <div class="img-container" style="height: 250px; overflow: hidden;">
                <img class="img_good" src="<?= $product['image'] ?>" alt="" style="width: 100%; height: 100%; object-fit: contain;">
            </div>
            <p class="figure_name" title="<?= $product['name'] ?>"><?= $product['name'] ?></p>
            <p class="description"><?= $product['description'] ?></p>
            <div class="cat_on_storage">
                <span>В наявності</span>
                <br>
                <span class="old_price"><?= number_format($product['old_price'], 2) ?>₴</span>
            </div>
        </a>
        <div class="price">
            <p class="price" style="display: inline;"><?= $product['price'] ?> ₴</p>
            <div>
                <button class="wishlist-btn" data-product-id="<?= $product['product_id'] ?>" style="background: none; border: none; padding: 0;">
                    <img class="love" src="../img/<?= $product['in_wishlist'] ? 'fav_love.svg' : 'love.svg' ?>" alt="Add to wishlist">
                </button>
                <form method="post" action="cart.php" style="display: inline;">
                    <input type="hidden" name="item_id" value="<?= $product['product_id'] ?>">
                    <input type="hidden" name="image" value="<?= $product['image'] ?>">
                    <input type="hidden" name="price" value="<?= $product['price'] ?>">
                    <button type="submit" name="action" value="add" style="background: none; border: none; padding: 0;">
                        <img class="cart-icon" src="../img/ShoppingCart.svg" alt="Add to cart">
                    </button>
                </form>
            </div>
        </div>
    </div>
    <?php
}
?>