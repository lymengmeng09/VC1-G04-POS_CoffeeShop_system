<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['user_id'])) :
 ?>
<div class="container">
    <h1>Welcome to PHP</h1>
</div>
<?php 
else: 
    $this->redirect("/users/login"); 
endif;   
?>
