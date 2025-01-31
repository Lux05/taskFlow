<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';
require_once 'includes/functions.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

$pageTitle = 'Add Task - TaskFlow';
$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = cleanInput($_POST['title'] ?? '');
    $description = cleanInput($_POST['description'] ?? '');
    $priority = $_POST['priority'] ?? 'medium';
    
    if (empty($title)) {
        $errors[] = "Title is required";
    }
    if (strlen($title) > 100) {
        $errors[] = "Title must be less than 100 characters";
    }
    if (!in_array($priority, ['low', 'medium', 'high'])) {
        $errors[] = "Invalid priority level";
    }
    
    if (empty($errors)) {
        if (createTask($_SESSION['user_id'], $title, $description, $priority)) {
            $success = true;
        } else {
            $errors[] = "Failed to create task";
        }
    }
}

require_once 'templates/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="mb-0">Add New Task</h3>
                <a href="index.php" class="btn btn-outline-secondary btn-sm">
                    Back to Tasks
                </a>
            </div>
            <div class="card-body p-4">
                <?php if ($success): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        Task created successfully!
                        <a href="index.php" class="alert-link">View all tasks</a>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo $error; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="mb-4">
                        <label for="title" class="form-label">Task Title</label>
                        <input type="text" class="form-control" id="title" name="title" 
                               value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''; ?>"
                               placeholder="Enter task title" required>
                    </div>
                    
                    <div class="mb-4">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="4" 
                                  placeholder="Enter task description"><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label">Priority</label>
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check" name="priority" id="low" value="low" 
                                   <?php echo (isset($_POST['priority']) && $_POST['priority'] == 'low') ? 'checked' : ''; ?>>
                            <label class="btn btn-outline-success" for="low">Low</label>

                            <input type="radio" class="btn-check" name="priority" id="medium" value="medium" 
                                   <?php echo (!isset($_POST['priority']) || $_POST['priority'] == 'medium') ? 'checked' : ''; ?>>
                            <label class="btn btn-outline-warning" for="medium">Medium</label>

                            <input type="radio" class="btn-check" name="priority" id="high" value="high" 
                                   <?php echo (isset($_POST['priority']) && $_POST['priority'] == 'high') ? 'checked' : ''; ?>>
                            <label class="btn btn-outline-danger" for="high">High</label>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Create Task</button>
                        <a href="index.php" class="btn btn-light">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'templates/footer.php'; ?>