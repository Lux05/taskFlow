<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';
require_once 'includes/functions.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

$pageTitle = 'My Tasks - TaskFlow';
$tasks = getTasks($_SESSION['user_id']);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] == 'updateStatus') {
        $task_id = $_POST['task_id'] ?? 0;
        $status = $_POST['status'] ?? '';
        if (updateTaskStatus($task_id, $_SESSION['user_id'], $status)) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
            exit;
        }
    }
}

require_once 'templates/header.php';
?>

<div class="row mb-4">
    <div class="col-md-8">
        <h2 class="mb-0">My Tasks</h2>
    </div>
    <div class="col-md-4 text-md-end">
        <a href="add_task.php" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Add New Task
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-header bg-light">
                <h5 class="mb-0">To Do</h5>
            </div>
            <div class="card-body p-2">
                <?php foreach ($tasks as $task): ?>
                    <?php if ($task['status'] == 'todo'): ?>
                        <div class="task-card card mb-2" data-task-id="<?php echo $task['id']; ?>">
                            <div class="card-body">
                                <h6 class="card-title mb-2">
                                    <?php echo htmlspecialchars($task['title']); ?>
                                </h6>
                                <p class="card-text small text-muted mb-2">
                                    <?php echo nl2br(htmlspecialchars($task['description'])); ?>
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge <?php echo getPriorityClass($task['priority']); ?>">
                                        <?php echo ucfirst($task['priority']); ?>
                                    </span>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-outline-secondary move-task" 
                                                data-status="in_progress">
                                            Start
                                        </button>
                                        <a href="edit_task.php?id=<?php echo $task['id']; ?>" 
                                           class="btn btn-sm btn-outline-primary">
                                            Edit
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">In Progress</h5>
            </div>
            <div class="card-body p-2">
                <?php foreach ($tasks as $task): ?>
                    <?php if ($task['status'] == 'in_progress'): ?>
                        <div class="task-card card mb-2" data-task-id="<?php echo $task['id']; ?>">
                            <div class="card-body">
                                <h6 class="card-title mb-2">
                                    <?php echo htmlspecialchars($task['title']); ?>
                                </h6>
                                <p class="card-text small text-muted mb-2">
                                    <?php echo nl2br(htmlspecialchars($task['description'])); ?>
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge <?php echo getPriorityClass($task['priority']); ?>">
                                        <?php echo ucfirst($task['priority']); ?>
                                    </span>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-outline-success move-task" 
                                                data-status="done">
                                            Complete
                                        </button>
                                        <a href="edit_task.php?id=<?php echo $task['id']; ?>" 
                                           class="btn btn-sm btn-outline-primary">
                                            Edit
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Done</h5>
            </div>
            <div class="card-body p-2">
                <?php foreach ($tasks as $task): ?>
                    <?php if ($task['status'] == 'done'): ?>
                        <div class="task-card card mb-2" data-task-id="<?php echo $task['id']; ?>">
                            <div class="card-body">
                                <h6 class="card-title mb-2 text-decoration-line-through">
                                    <?php echo htmlspecialchars($task['title']); ?>
                                </h6>
                                <p class="card-text small text-muted mb-2">
                                    <?php echo nl2br(htmlspecialchars($task['description'])); ?>
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge <?php echo getPriorityClass($task['priority']); ?>">
                                        <?php echo ucfirst($task['priority']); ?>
                                    </span>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-outline-secondary move-task" 
                                                data-status="todo">
                                            Reopen
                                        </button>
                                        <a href="edit_task.php?id=<?php echo $task['id']; ?>" 
                                           class="btn btn-sm btn-outline-primary">
                                            Edit
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.move-task').forEach(button => {
        button.addEventListener('click', function() {
            const taskId = this.closest('.task-card').dataset.taskId;
            const newStatus = this.dataset.status;
            
            fetch('index.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=updateStatus&task_id=${taskId}&status=${newStatus}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                        window.location.reload();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to update task status');
                });
        });
    });
});
</script>

<?php require_once 'templates/footer.php'; ?>