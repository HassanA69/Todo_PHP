<?php
include "config/database.php";
include "partials/header.php";
include "partials/notification.php";
include "classes/Task.php";
$database = new Database();
$db = $database->connect();
session_start();
$todo = new Task($db);

// var_dump($todo->read());

if ($_SERVER['REQUEST_METHOD'] == 'POST') {


    if (isset($_POST['add_task'])) {

        $todo->task = $_POST['task'];

        if ($todo->create()) {
            $_SESSION['message'] = "Task added successfully";
            $_SESSION['msg_type'] = "success";
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit();
        }
    } else if (isset($_POST['complete_task'])) {
        if ($todo->complete($_POST['id'])) {
            $_SESSION['message'] = "Task marked completed";
            $_SESSION['msg_type'] = "success";
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit();
        }
    } else if (isset($_POST['undo_complete_task'])) {
        if ($todo->undo_complete($_POST['id'])) {

            $_SESSION['message'] = 'Task marked incomplete';
            $_SESSION['msg_type'] = "success";
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit();
        }
    } else if (isset($_POST['delete_task'])) {
        if ($todo->delete($_POST['id'])) {

            $_SESSION['message'] = "Task deleted ";
            $_SESSION['msg_type'] = "error";
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit();
        }
    }
}

// FETCH Tasks
$tasks = $todo->read();

?>

<!-- notification container -->
<?php if (isset($_SESSION['message'])): ?>
    <div class="notification-container <?php echo isset($_SESSION['message']) ? 'show' : '' ?>">
        <div class="notification <?php echo $_SESSION['msg_type'] ?>">
            <?php echo $_SESSION['message']; ?>
            <?php unset($_SESSION['message']) ?>

        </div>
    </div>
<?php endif; ?>

<!-- Main container -->
<div class="container">
    <h1>Todo App</h1>

    <!-- Add Task Form -->
    <form method="POST">
        <input type="text" name="task" placeholder="Enter a new task " required>
        <button type="submit" name="add_task">Add task</button>
    </form>

    <!-- Display Tasks -->
    <ul>
        <?php while ($task = $tasks->fetch_assoc()): ?>
            <li class="completed">
                <span class="<?php echo $task['is_completed'] ? 'completed' : '' ?>">
                    <?php echo $task['task'] ?>
                </span>
                <div>

                    <?php if (!$task['is_completed']): ?>
                        <!-- Complete Task -->
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo $task['id'] ?>">
                            <button class="complete" type="submit" name="complete_task">Complete</button>
                        </form>

                    <?php else: ?>
                        <!-- Undo Completed Task -->
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo  $task['id'] ?>">
                            <button class="undo" type="submit" name="undo_complete_task">Undo</button>
                        </form>

                    <?php endif; ?>
                    <!-- Delete Task -->
                    <form onclick="return confirmDelete()" method="POST" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $task['id'] ?>">
                        <button class="delete" type="submit" name="delete_task">Delete</button>
                    </form>
                </div>
            </li>


        <?php endwhile; ?>
    </ul>
</div>

<script>
    function confirmDelete() {
        return confirm("Are you sure you want to delete this task?");
    }
</script>
<?php
include "partials/footer.php";
?>