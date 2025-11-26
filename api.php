<?php
require_once 'config.php';
requireLogin();

header('Content-Type: application/json');

try {
    $action = $_GET['action'] ?? '';
    $method = $_SERVER['REQUEST_METHOD'];

    switch ($action) {
        case 'get_domains':
            $stmt = $pdo->query("SELECT * FROM virtual_domains ORDER BY id ASC");
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            break;

        case 'get_aliases':
            $stmt = $pdo->query("
                SELECT a.*, d.name as domain_name 
                FROM virtual_aliases a 
                LEFT JOIN virtual_domains d ON a.domain_id = d.id 
                ORDER BY a.id ASC
            ");
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            break;

        case 'get_emails':
            $domain_id = $_GET['domain_id'] ?? '';
            $search = $_GET['search'] ?? '';
            
            $sql = "SELECT u.*, d.name as domain_name 
                    FROM virtual_users u 
                    LEFT JOIN virtual_domains d ON u.domain_id = d.id 
                    WHERE 1=1";
            
            $params = [];
            
            if (!empty($domain_id)) {
                $sql .= " AND u.domain_id = ?";
                $params[] = $domain_id;
            }
            
            if (!empty($search)) {
                $sql .= " AND u.email LIKE ?";
                $params[] = "%$search%";
            }
            
            $sql .= " ORDER BY u.id ASC";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            break;

        case 'get_domain':
            $id = $_GET['id'] ?? '';
            $stmt = $pdo->prepare("SELECT * FROM virtual_domains WHERE id = ?");
            $stmt->execute([$id]);
            echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
            break;

        case 'get_alias':
            $id = $_GET['id'] ?? '';
            $stmt = $pdo->prepare("SELECT * FROM virtual_aliases WHERE id = ?");
            $stmt->execute([$id]);
            echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
            break;

        case 'get_email':
            $id = $_GET['id'] ?? '';
            $stmt = $pdo->prepare("SELECT * FROM virtual_users WHERE id = ?");
            $stmt->execute([$id]);
            echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
            break;

        case 'add_domain':
            if ($method !== 'POST') throw new Exception('Method not allowed');
            
            $name = $_POST['name'] ?? '';
            if (empty($name)) {
                echo json_encode(['success' => false, 'message' => 'Domain name is required']);
                break;
            }
            
            $stmt = $pdo->prepare("INSERT INTO virtual_domains (name) VALUES (?)");
            $stmt->execute([$name]);
            echo json_encode(['success' => true, 'message' => 'Domain added successfully']);
            break;

        case 'add_alias':
            if ($method !== 'POST') throw new Exception('Method not allowed');
            
            $domain_id = $_POST['domain_id'] ?? '';
            $source = $_POST['source'] ?? '';
            $destination = $_POST['destination'] ?? '';
            
            if (empty($domain_id) || empty($source) || empty($destination)) {
                echo json_encode(['success' => false, 'message' => 'All fields are required']);
                break;
            }
            
            $stmt = $pdo->prepare("INSERT INTO virtual_aliases (domain_id, source, destination) VALUES (?, ?, ?)");
            $stmt->execute([$domain_id, $source, $destination]);
            echo json_encode(['success' => true, 'message' => 'Alias added successfully']);
            break;

        case 'add_email':
            if ($method !== 'POST') throw new Exception('Method not allowed');
            
            $domain_id = $_POST['domain_id'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            
            if (empty($domain_id) || empty($email) || empty($password)) {
                echo json_encode(['success' => false, 'message' => 'All fields are required']);
                break;
            }
            
            $password_hash = generatePasswordHash($password);
            $stmt = $pdo->prepare("INSERT INTO virtual_users (domain_id, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$domain_id, $email, $password_hash]);
            echo json_encode(['success' => true, 'message' => 'Email account added successfully']);
            break;

        case 'update_domain':
            if ($method !== 'POST') throw new Exception('Method not allowed');
            
            $id = $_POST['id'] ?? '';
            $name = $_POST['name'] ?? '';
            
            if (empty($id) || empty($name)) {
                echo json_encode(['success' => false, 'message' => 'Domain ID and name are required']);
                break;
            }
            
            $stmt = $pdo->prepare("UPDATE virtual_domains SET name = ? WHERE id = ?");
            $stmt->execute([$name, $id]);
            echo json_encode(['success' => true, 'message' => 'Domain updated successfully']);
            break;

        case 'update_alias':
            if ($method !== 'POST') throw new Exception('Method not allowed');
            
            $id = $_POST['id'] ?? '';
            $domain_id = $_POST['domain_id'] ?? '';
            $source = $_POST['source'] ?? '';
            $destination = $_POST['destination'] ?? '';
            
            if (empty($id) || empty($domain_id) || empty($source) || empty($destination)) {
                echo json_encode(['success' => false, 'message' => 'All fields are required']);
                break;
            }
            
            $stmt = $pdo->prepare("UPDATE virtual_aliases SET domain_id = ?, source = ?, destination = ? WHERE id = ?");
            $stmt->execute([$domain_id, $source, $destination, $id]);
            echo json_encode(['success' => true, 'message' => 'Alias updated successfully']);
            break;

        case 'update_email':
            if ($method !== 'POST') throw new Exception('Method not allowed');
            
            $id = $_POST['id'] ?? '';
            $domain_id = $_POST['domain_id'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            
            if (empty($id) || empty($domain_id) || empty($email)) {
                echo json_encode(['success' => false, 'message' => 'Domain, email, and ID are required']);
                break;
            }
            
            if (!empty($password)) {
                $password_hash = generatePasswordHash($password);
                $stmt = $pdo->prepare("UPDATE virtual_users SET domain_id = ?, email = ?, password = ? WHERE id = ?");
                $stmt->execute([$domain_id, $email, $password_hash, $id]);
            } else {
                $stmt = $pdo->prepare("UPDATE virtual_users SET domain_id = ?, email = ? WHERE id = ?");
                $stmt->execute([$domain_id, $email, $id]);
            }
            echo json_encode(['success' => true, 'message' => 'Email account updated successfully']);
            break;

        case 'delete_domain':
            if ($method !== 'POST') throw new Exception('Method not allowed');
            
            $id = $_POST['id'] ?? '';
            if (empty($id)) {
                echo json_encode(['success' => false, 'message' => 'Domain ID is required']);
                break;
            }

            $stmt = $pdo->prepare("SELECT COUNT(*) FROM virtual_users WHERE domain_id = ?");
            $stmt->execute([$id]);
            $user_count = $stmt->fetchColumn();
            
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM virtual_aliases WHERE domain_id = ?");
            $stmt->execute([$id]);
            $alias_count = $stmt->fetchColumn();
            
            if ($user_count > 0 || $alias_count > 0) {
                echo json_encode(['success' => false, 'message' => 'Cannot delete domain with existing users or aliases']);
                break;
            }
            
            $stmt = $pdo->prepare("DELETE FROM virtual_domains WHERE id = ?");
            $stmt->execute([$id]);
            echo json_encode(['success' => true, 'message' => 'Domain deleted successfully']);
            break;

        case 'delete_alias':
            if ($method !== 'POST') throw new Exception('Method not allowed');
            
            $id = $_POST['id'] ?? '';
            if (empty($id)) {
                echo json_encode(['success' => false, 'message' => 'Alias ID is required']);
                break;
            }
            
            $stmt = $pdo->prepare("DELETE FROM virtual_aliases WHERE id = ?");
            $stmt->execute([$id]);
            echo json_encode(['success' => true, 'message' => 'Alias deleted successfully']);
            break;

        case 'delete_email':
            if ($method !== 'POST') throw new Exception('Method not allowed');
            
            $id = $_POST['id'] ?? '';
            if (empty($id)) {
                echo json_encode(['success' => false, 'message' => 'Email ID is required']);
                break;
            }
            
            $stmt = $pdo->prepare("DELETE FROM virtual_users WHERE id = ?");
            $stmt->execute([$id]);
            echo json_encode(['success' => true, 'message' => 'Email account deleted successfully']);
            break;

        default:
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'API Error: ' . $e->getMessage()]);
}
?>