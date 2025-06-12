<?php
include 'db.php';

$sql = "SELECT * FROM tasks ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>PHP Task Managerr</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="mb-4">Task Manager</h2>
    <a href="add_task.php" class="btn btn-primary mb-3">Add New Task</a>
    <table class="table table-bordered bg-white">
        <thead class="table-light">
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['title']) ?></td>
                        <td><?= htmlspecialchars($row['description']) ?></td>
                        <td>
                            <button class="btn btn-sm status-btn <?= $row['status'] === 'completed' ? 'btn-success' : 'btn-secondary' ?>"
                                data-id="<?= $row['id'] ?>"
                                data-status="<?= $row['status'] === 'completed' ? 'pending' : 'completed' ?>">
                                <?= ucfirst($row['status']) ?>
                            </button>
                        </td>
                        <td><?= $row['created_at'] ?></td>
                        <td>
                            <a href="edit_task.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                            <a href="delete_task.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center">No tasks found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function(){
        $('.status-btn').click(function(){
            var button = $(this);
            var taskId = button.data('id');
            var newStatus = button.data('status');

            $.post('update_status.php', {id: taskId, status: newStatus}, function(response){
                if (response.success) {
                    location.reload();
                } else {
                    alert('Failed to update task status.');
                }
            }, 'json');
        });
    });
</script>
</body>
</html>
