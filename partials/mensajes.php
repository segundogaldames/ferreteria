<?php if(isset($_SESSION['success'])): ?>
    <div class="alert alert-success" role="alert">
        <?php echo $_SESSION['success']; ?>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if(isset($_SESSION['danger'])): ?>
    <div class="alert alert-danger" role="alert">
        <?php echo $_SESSION['danger']; ?>
    </div>
    <?php unset($_SESSION['danger']); ?>
<?php endif; ?>