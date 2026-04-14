<?php

class Controller
{
    protected function view($view, $data = [])
    {
        extract($data);
        $viewPath = BASE_PATH . 'app/views/' . $view . '.php';

        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            die("View not found: {$view}");
        }
    }

    protected function model($model)
    {
        $modelPath = BASE_PATH . 'app/models/' . $model . '.php';

        if (file_exists($modelPath)) {
            require_once $modelPath;
            return new $model();
        } else {
            die("Model not found: {$model}");
        }
    }

    protected function redirect($url)
    {
        header('Location: ' . BASE_URL . $url);
        exit;
    }

    protected function json($data, $statusCode = 200)
    {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }

    protected function isLoggedIn()
    {
        // Check if user_id exists in session
        if (!isset($_SESSION['user_id'])) {
            return false;
        }

        // Check session timeout
        if (isset($_SESSION['last_activity'])) {
            $elapsed = time() - $_SESSION['last_activity'];
            
            if ($elapsed > SESSION_TIMEOUT) {
                // Session expired, destroy it
                session_unset();
                session_destroy();
                return false;
            }
        }

        // Update last activity time
        $_SESSION['last_activity'] = time();
        
        return true;
    }

    protected function requireLogin()
    {
        if (!$this->isLoggedIn()) {
            $this->redirect('auth/login');
        }
    }

    protected function requireRole($allowedRoles = [])
    {
        $this->requireLogin();

        if (!in_array($_SESSION['role'], $allowedRoles)) {
            $this->redirect('auth/unauthorized');
        }
    }

    protected function validate($data, $rules)
    {
        $errors = [];

        foreach ($rules as $field => $rule) {
            $ruleList = explode('|', $rule);

            foreach ($ruleList as $r) {
                if ($r === 'required' && empty($data[$field])) {
                    $errors[$field] = ucfirst(str_replace('_', ' ', $field)) . ' wajib diisi';
                    break;
                }

                if (strpos($r, 'min:') === 0) {
                    $min = (int)substr($r, 4);
                    if (strlen($data[$field]) < $min) {
                        $errors[$field] = ucfirst(str_replace('_', ' ', $field)) . " minimal {$min} karakter";
                        break;
                    }
                }

                if (strpos($r, 'max:') === 0) {
                    $max = (int)substr($r, 4);
                    if (strlen($data[$field]) > $max) {
                        $errors[$field] = ucfirst(str_replace('_', ' ', $field)) . " maksimal {$max} karakter";
                        break;
                    }
                }

                if ($r === 'email' && !filter_var($data[$field], FILTER_VALIDATE_EMAIL)) {
                    $errors[$field] = 'Format email tidak valid';
                    break;
                }

                if ($r === 'numeric' && !is_numeric($data[$field])) {
                    $errors[$field] = ucfirst(str_replace('_', ' ', $field)) . ' harus berupa angka';
                    break;
                }
            }
        }

        return $errors;
    }

    protected function sanitize($data)
    {
        if (is_array($data)) {
            return array_map([$this, 'sanitize'], $data);
        }
        return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
    }

    protected function uploadFile($file, $destination = 'dokumen')
    {
        if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'message' => 'File upload gagal'];
        }

        $fileSize = $file['size'];
        $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if ($fileSize > MAX_FILE_SIZE) {
            return ['success' => false, 'message' => 'Ukuran file maksimal 5MB'];
        }

        if (!in_array($fileExt, ALLOWED_EXTENSIONS)) {
            return ['success' => false, 'message' => 'Format file tidak diizinkan'];
        }

        $fileName = uniqid() . '_' . time() . '.' . $fileExt;
        $uploadPath = UPLOAD_PATH . $destination . '/' . $fileName;

        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            return ['success' => true, 'filename' => $fileName, 'path' => $destination . '/' . $fileName];
        }

        return ['success' => false, 'message' => 'Gagal menyimpan file'];
    }
}
