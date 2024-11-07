<?php

// Підключення до бази даних
$dbObject = new mysqli('localhost', 'root', '', 'users_db');

// Перевірка на помилки з'єднання
if ($dbObject->connect_error) {
    die("Connection failed: " . $dbObject->connect_error);
}
/**
 * Клас для управління користувачами
 * Демонструє використання ООП та інкапсуляції
 */
class UserManager
{
    // Приватна змінна для зберігання об'єкта з'єднання з базою даних
    private $db;
    // Конструктор, що ініціалізує з'єднання з базою даних
    public function __construct($dbConnection)
    {
        $this->db = $dbConnection;
    }

    /**
     * Метод для отримання даних користувачів
     * @return array Масив даних всіх користувачів
     */
    public function get_all_users(): array
    {
        $query = "SELECT * FROM users";
        $result = $this->db->query($query);

        // Повертаємо масив усіх користувачів
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}

// Створення об'єкта UserManager та виклик методу для отримання даних всіх користувачів
$userManager = new UserManager($dbObject);
$users = $userManager->get_all_users();

/**
 * Уявімо що масив має такі значення:
 * $users = [
 * ['id' => 1, 'name' => 'Alice', 'is_admin' => true],
 * ['id' => 2, 'name' => 'Bob', 'is_admin' => false],
 * ];
 */

/**
 * Обробка масиву даних користувачів за допомогою циклу for
 */
for ($i = 0; $i < count($users); $i++) {
    echo "User ID: " . $users[$i]['id'] . "\n";
    echo "User Name: " . $users[$i]['name'] . "\n";

    // Перевіряємо, чи є користувач адміністратором
    if ($users[$i]['is_admin']) {
        echo "Status: Admin\n";
    } else {
        echo "Status: Regular user\n";
    }
    echo "--------------------\n"; // Роздільник для кращого вигляду
    echo "User \"Alice\" has an ID of " . $users[0]['id'] . "\n"; // User "Alice" has an ID of 1

}

echo 'User ID: ' . $users[$i]['id'] . "\n"; // User ID:
echo 'User Name: ' . $users[$i]['name'] . "\n" // User Name:

echo "User ID: " . $users[$i]['id'] . "\n"; // User ID: 1
echo "User Name: " . $users[$i]['name'] . "\n"; // User Name: Alice



/**
 * **********************************************
 * Далі йде код який я переробляв для прикладів
 * **********************************************
 */

/**
 * Обробка масиву даних користувачів за допомогою циклу foreach
 */
foreach ($users as $user) {
    echo "User ID: " . $user['id'] . "\n";
    echo "User Name: " . $user['name'] . "\n";

    // Перевіряємо, чи є користувач адміністратором
    if ($user['is_admin']) {
        echo "Status: Admin\n";
    } else {
        echo "Status: Regular user\n";
    }
}

/**
 * Обробка масиву даних користувачів за допомогою циклу while
 */
$i = 0;
while ($i < count($users)) {
    echo "User ID: " . $users[$i]['id'] . "\n";
    echo "User Name: " . $users[$i]['name'] . "\n";

    // Перевіряємо, чи є користувач адміністратором
    if ($users[$i]['is_admin']) {
        echo "Status: Admin\n";
    } else {
        echo "Status: Regular user\n";
    }
    $i++;
}

/**
 * Обробка масиву даних користувачів за допомогою циклу do while
 */
$i = 0;
do {
    echo "User ID: " . $users[$i]['id'] . "\n";
    echo "User Name: " . $users[$i]['name'] . "\n";

    // Перевіряємо, чи є користувач адміністратором
    if ($users[$i]['is_admin']) {
        echo "Status: Admin\n";
    } else {
        echo "Status: Regular user\n";
    }
    $i++;
} while ($i < count($users));

/**
 * Перевірка статусу користувача
 */
if ($user['is_admin']) {
    echo "Status: Admin\n";
} elseif ($user['is_moderator']) {
    echo "Status: Moderator\n";
} else {
    echo "Status: Regular user\n";
}


/**
 * Перевірка статусу користувача
 */
switch ($user['type']) {
    case 'admin':
        echo "User is an admin.\n";
        break;

    case 'moderator':
        echo "User is a moderator.\n";
        break;

    case 'regular':
        echo "User is a regular user.\n";
        break;

    default:
        echo "User type is unknown.\n";
        break;
}

/**
 * Неправильний приклад визначення статусу користувача за типом
 */
switch ($user['type']) {
    case 'admin':
        echo "User is an admin.\n"; break;

    case 'moderator':
        echo "User is a moderator.\n"; break;

    case 'regular':
        echo "User is a regular user.\n"; break;

    default: echo "User type is unknown.\n"; break;
}


