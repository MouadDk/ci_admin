<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PME Login</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Font Awesome for Icons (required for the eye icon) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    
    <!-- Link to our new external stylesheet -->
    <link rel="stylesheet" href="<?php echo base_url('assets/css/login_style.css'); ?>">

</head>
<body>

    <div class="login-container">
        <h2>Maroc PME</h2>
        <p>Please enter your credentials to login.</p>

        <?php if($this->session->flashdata('error')): ?>
            <div class="alert"><?php echo $this->session->flashdata('error'); ?></div>
        <?php endif; ?>

        <?php if(validation_errors()): ?>
             <div class="alert"><?php echo validation_errors(); ?></div>
        <?php endif; ?>

        <?php echo form_open('auth/login_process'); ?>

            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" class="form-control" value="<?php echo set_value('username'); ?>" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="password-wrapper">
                    <input type="password" name="password" id="password" class="form-control" required>
                    <!-- The eye icon: starts as a closed eye -->
                    <i class="fas fa-eye" id="togglePassword"></i>
                </div>
            </div>

            <button type="submit" class="btn-submit">Login</button>

        <?php echo form_close(); ?>

    </div>

    <!-- Link to our new external JavaScript file (at the end of body for faster page load) -->
    <script src="<?php echo base_url('assets/js/login_script.js'); ?>"></script>

</body>
</html>