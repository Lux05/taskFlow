<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';
require_once 'includes/functions.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

$task_id = $_GET['id'] ?? 0;
$task = getTask($task_id, $_SESSION['user_id']);

if (!$task) {
    header("Location: index.php");
    exit();
}

$pageTitle = 'Edit Task - TaskFlow';
$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && $_POST['action'] == 'delete') {
        if (deleteTask($task_id, $_SESSION['user_id'])) {
            header("Location: index.php");
            exit();
        } else {
            $errors[] = "Failed to delete task";
        }
    } else {
        $title = cleanInput($_POST['title'] ?? '');
        $description = cleanInput($_POST['description'] ?? '');
        $priority = $_POST['priority'] ?? 'medium';
        $status = $_POST['status'] ?? 'todo';
        
        if (empty($title)) {
            $errors[] = "Title is required";
        }
        if (strlen($title) > 100) {
            $errors[] = "Title must be less than 100 characters";
        }
        if (!in_array($priority, ['low', 'medium', 'high'])) {
            $errors[] = "Invalid priority level";
        }
        if (!in_array($status, ['todo', 'in_progress', 'done'])) {
            $errors[] = "Invalid status";
        }
        
        if (empty($errors)) {
            if (updateTask($task_id, $_SESSION['user_id'], $title, $description, $priority, $status)) {
                $success = true;
                $task = getTask($task_id, $_SESSION['user_id']); // Обновляем данные
            } else {
                $errors[] = "Failed to update task";
            }
        }
    }
}

require_once 'templates/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="mb-0">Edit Task</h3>
                <div>
                    <button type="button" class="btn btn-outline-danger btn-sm me-2" data-bs-toggle="modal" data-bs-target="#deleteModal">
                        Delete Task
                    </button>
                    <a href="index.php" class="btn btn-outline-secondary btn-sm">
                        Back to Tasks
                    </a>
                </div>
            </div>
            <div class="card-body p-4">
                <?php if ($success): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        Task updated successfully!
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
                               value="<?php echo htmlspecialchars($task['title']); ?>"
                               placeholder="Enter task title" required>
                    </div>
                    
                    <div class="mb-4">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="4" 
                                  placeholder="Enter task description"><?php echo htmlspecialchars($task['description']); ?></textarea>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label">Priority</label>
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check" name="priority" id="low" value="low" 
                                   <?php echo $task['priority'] == 'low' ? 'checked' : ''; ?>>
                            <label class="btn btn-outline-success" for="low">Low</label>

                            <input type="radio" class="btn-check" name="priority" id="medium" value="medium" 
                                   <?php echo $task['priority'] == 'medium' ? 'checked' : ''; ?>>
                            <label class="btn btn-outline-warning" for="medium">Medium</label>

                            <input type="radio" class="btn-check" name="priority" id="high" value="high" 
                                   <?php echo $task['priority'] == 'high' ? 'checked' : ''; ?>>
                            <label class="btn btn-outline-danger" for="high">High</label>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Status</label>
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check" name="status" id="todo" value="todo" 
                                   <?php echo $task['status'] == 'todo' ? 'checked' : ''; ?>>
                            <label class="btn btn-outline-secondary" for="todo">To Do</label>

                            <input type="radio" class="btn-check" name="status" id="in_progress" value="in_progress" 
                                   <?php echo $task['status'] == 'in_progress' ? 'checked' : ''; ?>>
                            <label class="btn btn-outline-info" for="in_progress">In Progress</label>

                            <input type="radio" class="btn-check" name="status" id="done" value="done" 
                                   <?php echo $task['status'] == 'done' ? 'checked' : ''; ?>>
                            <label class="btn btn-outline-success" for="done">Done</label>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Update Task</button>
                        <a href="index.php" class="btn btn-light">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this task? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" action="" style="display: inline;">
                    <input type="hidden" name="action" value="delete">
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'templates/footer.php'; ?>