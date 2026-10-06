<?php
session_start();

require 'db.php';
require 'functions.php';

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['complete_task_id'])) {
    $taskId = filter_input(INPUT_POST, 'complete_task_id', FILTER_VALIDATE_INT);
    $submittedToken = $_POST['csrf_token'] ?? '';

    if (!is_string($submittedToken) || !hash_equals($_SESSION['csrf_token'], $submittedToken)) {
        $_SESSION['message'] = 'Unable to complete the task. Please try again.';
    } elseif ($taskId === false || $taskId === null || $taskId < 1) {
        $_SESSION['message'] = 'Invalid task.';
    } else {
        $completeStmt = $pdo->prepare(
            'UPDATE tasks SET completed = 1 WHERE id = ? AND completed = 0'
        );
        $completeStmt->execute([$taskId]);

        $_SESSION['message'] = $completeStmt->rowCount() === 1
            ? 'Task marked as completed.'
            : 'Task is already completed or could not be found.';
    }

    header('Location: index.php');
    exit;
}

$stmt = $pdo->query(
    "SELECT * FROM tasks ORDER BY due_date ASC"
);

$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

$countStmt = $pdo->query(
    "SELECT COUNT(*) FROM tasks WHERE completed = 1"
);

$completedCount = (int) $countStmt->fetchColumn();
$totalCount = count($tasks);
$pendingCount = $totalCount - $completedCount;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Student Task Manager</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="dashboard-page">

<main class="container dashboard">
    <header class="page-header">
        <div>
            <p class="eyebrow">STUDENT TASK MANAGER</p>
            <h1>My tasks</h1>
            <p class="page-subtitle">Stay organized and keep your assignments on track.</p>
        </div>
        <a href="create.php" class="btn add-task-btn">
            <span aria-hidden="true">+</span> Add new task
        </a>
    </header>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="message" role="status">
            <?php echo clean($_SESSION['message']); ?>
        </div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <section class="summary-grid" aria-label="Task summary">
        <article class="summary-card">
            <span class="summary-label">All tasks</span>
            <strong class="summary-number"><?php echo $totalCount; ?></strong>
        </article>
        <article class="summary-card">
            <span class="summary-label">Pending</span>
            <strong class="summary-number"><?php echo $pendingCount; ?></strong>
        </article>
        <article class="summary-card">
            <span class="summary-label">Completed</span>
            <strong class="summary-number"><?php echo $completedCount; ?></strong>
        </article>
    </section>

    <section class="task-panel" aria-labelledby="task-list-heading">
        <div class="panel-heading">
            <div>
                <h2 id="task-list-heading">Task list</h2>
                <p>Your assignments, deadlines, and progress in one place.</p>
            </div>
        </div>

        <div class="table-scroll">
            <table class="task-table">
                <thead>
                    <tr>
                        <th scope="col">Task</th>
                        <th scope="col">Category</th>
                        <th scope="col">Priority</th>
                        <th scope="col">Due date</th>
                        <th scope="col">Status</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($tasks)): ?>
                        <tr>
                            <td colspan="6" class="empty-state">
                                <span class="empty-icon" aria-hidden="true">✓</span>
                                <strong>No tasks yet</strong>
                                <span>Add your first task to get started.</span>
                                <a href="create.php" class="empty-link">Create a task</a>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($tasks as $task): ?>
                            <?php
                            $isCompleted = (int) $task['completed'] === 1;
                            $priority = strtolower((string) $task['priority']);
                            $priorityClass = in_array($priority, ['low', 'medium', 'high'], true)
                                ? $priority
                                : 'default';
                            ?>
                            <tr>
                                <td class="task-name">
                                    <strong><?php echo clean($task['title']); ?></strong>
                                    <?php if (!empty($task['description'])): ?>
                                        <span><?php echo clean($task['description']); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="category-label">
                                        <?php echo clean($task['category'] ?: 'Uncategorized'); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="priority-badge priority-<?php echo $priorityClass; ?>">
                                        <?php echo clean($task['priority'] ?: '—'); ?>
                                    </span>
                                </td>
                                <td class="due-date">
                                    <?php echo clean($task['due_date'] ?: 'No due date'); ?>
                                </td>
                                <td>
                                    <span class="status-badge <?php echo $isCompleted ? 'status-completed' : 'status-pending'; ?>">
                                        <span class="status-dot" aria-hidden="true"></span>
                                        <?php echo $isCompleted ? 'Completed' : 'Pending'; ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="task-actions">
                                        <?php if (!$isCompleted): ?>
                                            <form class="complete-form" method="post" action="index.php">
                                                <input type="hidden" name="csrf_token" value="<?php echo clean($_SESSION['csrf_token']); ?>">
                                                <input type="hidden" name="complete_task_id" value="<?php echo (int) $task['id']; ?>">
                                                <button class="action-link complete-link" type="submit">Complete</button>
                                            </form>
                                        <?php endif; ?>
                                        <a class="action-link edit-link" href="edit.php?id=<?php echo (int) $task['id']; ?>">Edit</a>
                                        <a
                                            class="action-link delete-link"
                                            href="delete.php?id=<?php echo (int) $task['id']; ?>"
                                            onclick="return confirm('Are you sure you want to delete this task?')"
                                        >Delete</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</main>

</body>
</html>
