<?php
$conn = new mysqli("localhost", "root", "", "pos-s");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $qty = $_POST['qty'];

    // Handling image upload
    $imagePath = "";
    if (!empty($_FILES['image']['name'])) {
        $imagePath = "uploads/" . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
    } else {
        // Keep existing image if no new one is uploaded
        $query = "SELECT image FROM products WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($existingImage);
        $stmt->fetch();
        $stmt->close();
        $imagePath = $existingImage;
    }

    // Update the product
    $sql = "UPDATE products SET name=?, price=?, qty=?, image=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdisi", $name, $price, $qty, $imagePath, $id);

    if ($stmt->execute()) {
        echo "<script>alert('Product updated successfully!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Error updating product.'); window.location='index.php';</script>";
    }

    $stmt->close();
}

$conn->close();
?>
