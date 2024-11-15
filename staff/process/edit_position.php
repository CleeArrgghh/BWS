<?php
include '../../db_connection/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $position_id = $_POST['position_id'];
    $position_name = $_POST['positionName'];
    $description = $_POST['positionDescription'];

    $query = "UPDATE tbl_positions SET position_name='$position_name', description='$description' WHERE position_id=$position_id";
    mysqli_query($conn, $query);
    header("Location: ../../staff/manage_staff.php? succesful update the data.");
    exit;
} else {
    $position_id = $_GET['position_id'];
    $query = "SELECT * FROM tbl_positions WHERE position_id=$position_id";
    $result = mysqli_query($conn, $query);
    $position = mysqli_fetch_assoc($result);
}
?>
<!DOCTYPE html>
<html>
<!-- Similar HTML structure as index.php -->
<form action="edit_position.php" method="POST">
    <input type="hidden" name="position_id" value="<?= $position['position_id'] ?>">
    <input type="text" name="positionName" value="<?= $position['position_name'] ?>" required>
    <textarea name="positionDescription" required><?= $position['description'] ?></textarea>
    <button type="submit">Update</button>
</form>
