<form action="?page=login&act=login" method="post">
    <input type="text" name="name" placeholder="Логин" <?=param('name') ?>>
    <input type="password" name="password" placeholder="Пароль">
    <button type="submit">Вход</button>
</form>