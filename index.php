<?php include 'includes/header.php'; ?>

<div class="container mt-5 text-center">
    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
        <div class="alert alert-success">
            Cadastro realizado com sucesso! Por favor, verifique seu email para ativar sua conta.
        </div>
    <?php endif; ?>

    <h1>Bem-vindo ao Meu Sistema de Links Personalizados</h1>
    <p>Crie e compartilhe seus links personalizados em um único lugar. Organize seus links em categorias, personalize seu perfil, e muito mais!</p>
    <a href="https://projetos.rajo.com.br/atletica/user/register.php" class="btn btn-primary btn-lg">Cadastre-se</a>
    <a href="https://projetos.rajo.com.br/atletica/user/login.php" class="btn btn-secondary btn-lg">Login</a>
</div>

<?php include 'includes/footer.php'; ?>
