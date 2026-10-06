<?php
session_start();

require 'db.php';
require 'functions.php';

$error = "";

if(isset($_POST['submit']))
{
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $priority = $_POST['priority'];
    $due_date = $_POST['due_date'];

    if(validateTask($title))
    {
        $sql = "INSERT INTO tasks
        (title,description,category,priority,due_date)
        VALUES(?,?,?,?,?)";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            $title,
            $description,
            $category,
            $priority,
            $due_date
        ]);

        $_SESSION['message'] =
        "Task Added Successfully";

        header("Location:index.php");
        exit;
    }
    else
    {
        $error="Task title is required";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Add Task</title>
<link rel="stylesheet" href="style.css">
</head>

<body>

<div class="container">

<h2>Add Task</h2>

<p style="color:red;">
<?php echo $error; ?>
</p>

<form method="POST">

<label>Title</label>
<input type="text" name="title">

<label>Description</label>
<textarea name="description"></textarea>

<label>Category</label>
<input type="text" name="category">

<label>Priority</label>
<select name="priority">
    <option>Low</option>
    <option>Medium</option>
    <option>High</option>
</select>

<label>Due Date</label>
<input type="date" name="due_date">

<br><br>

<button
type="submit"
name="submit"
class="btn">
Save Task
</button>

</form>

<br>

<a href="index.php">
Back
</a>

</div>

</body>
</html>