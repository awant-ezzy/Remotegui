<!DOCTYPE html>
<html>
    <link rel="stylesheet" type="text/css" href="styles.css">
<head>
    <title>Mikrotik Remote Manager</title>
</head>
<body>
    <h1>Mikrotik Remote Manager</h1>

    <h2>Create Account</h2>
    <form action="create_account.php" method="post">
        Username: <input type="text" name="username" required><br>
        Password: <input type="password" name="password" required><br>
        Port: <input type="number" name="port" required><br>
        <button type="submit">Create Account</button>
    </form>

    <h2>Delete Account</h2>
    <form action="delete_account.php" method="post">
        Username: <input type="text" name="username" required><br>
        <button type="submit">Delete Account</button>
    </form>
</body>
</html>
