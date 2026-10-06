<?php
session_start();

require 'db.php';

$id = $_GET['id'];

// Get selected record
$stmt = $pdo->prepare(
    "SELECT * FROM tasks WHERE id=?"
);

$stmt->execute([$id]);

$task = $stmt->fetch(PDO::FETCH_ASSOC);

if(isset($_POST['update']))
{
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $priority = $_POST['priority'];
    $due_date = $_POST['due_date'];
    $completed = $_POST['completed'];

    $sql = "UPDATE tasks
            SET title=?,
                description=?,
                category=?,
                priority=?,
                due_date=?,
                completed=?
            WHERE id=?";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        $title,
        $description,
        $category,
        $priority,
        $due_date,
        $completed,
        $id
    ]);

    $_SESSION['message'] =
    "Task Updated Successfully";

    header("Location:index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Task</title>
<link rel="stylesheet" href="style.css">
</head>

<body>

<div class="container">

<h2>Edit Task</h2>

<form method="POST">

<label>Title</label>

<input
type="text"
name="title"
value="<?php echo $task['title']; ?>">

<label>Description</label>

<textarea
name="description"><?php echo $task['description']; ?></textarea>

<label>Category</label>

<input
type="text"
name="category"
value="<?php echo $task['category']; ?>">

<label>Priority</label>

<select name="priority">

<option value="Low">Low</option>

<option value="Medium">Medium</option>

<option value="High">High</option>

</select>

<label>Due Date</label>

<input
type="date"
name="due_date"
value="<?php echo $task['due_date']; ?>">

<label>Status</label>

<select name="completed">

<option value="0">Pending</option>

<option value="1">Completed</option>

</select>

<br><br>

<button
type="submit"
name="update"
class="btn">
Update
</button>

</form>

</div>

</body>
</html>