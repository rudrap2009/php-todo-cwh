<?php
session_start();
require 'csrf.php';

if (!isset($_SESSION['todos'])) {
    $_SESSION['todos'] = [];
}

$action = $_POST['action'] ?? '';
if ($action === 'add') {
    check_csrf();
    $text = trim($_POST['todo'] ?? '');
    if ($text !== '') {
        $_SESSION['todos'][] = ['text' => htmlspecialchars($text), 'done' => false];
    }
}
elseif ($action === 'toggle') {
    check_csrf();
    $idx = intval($_POST['idx']);
    if (isset($_SESSION['todos'][$idx])) {
        $_SESSION['todos'][$idx]['done'] = !$_SESSION['todos'][$idx]['done'];
    }
}
elseif ($action === 'delete') {
    check_csrf();
    $idx = intval($_POST['idx']);
    if (isset($_SESSION['todos'][$idx])) {
        array_splice($_SESSION['todos'], $idx, 1);
    }
}
elseif ($action === 'clear') {
    check_csrf();
    $_SESSION['todos'] = [];
}

$total = count($_SESSION['todos']);
$completed = count(array_filter($_SESSION['todos'], fn($t) => $t['done']));
$remaining = $total - $completed;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Beautiful PHP Todo App</title>
<link rel="stylesheet" href="style.css">
<link rel="javascript" href="script.js">
</head>
<body>
  <header><h1>My Todo List</h1></header>

  <div class="container">
    <div class="form">
      <form method="POST">
        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
        <input type="text" name="todo" placeholder="Add a new todo..." required>
        <button type="submit" name="action" value="add">Add</button>
      </form>
    </div>

    <?php if ($total === 0): ?>
      <div class="empty">You're all caught up! 🎉</div>
    <?php else: ?>
      <ul class="todos">
        <?php foreach ($_SESSION['todos'] as $i => $t): ?>
          <li class="<?= $t['done'] ? 'done' : '' ?>">
            <form method="POST" class="todo-form">
              <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
              <input type="hidden" name="idx" value="<?= $i ?>">
              <button type="submit" name="action" value="toggle">
                <?= $t['done'] ? '✅' : '⬜' ?>
              </button>
              <span><?= $t['text'] ?></span>
              <button type="submit" name="action" value="delete">🗑️</button>
            </form>
          </li>
        <?php endforeach; ?>
      </ul>
      <form method="POST" class="clear-form">
        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
        <button type="submit" name="action" value="clear">Clear All</button>
      </form>
    <?php endif; ?>

    <div class="stats">
      <div>Total: <strong><?= $total ?></strong></div>
      <div>Completed: <strong><?= $completed ?></strong></div>
      <div>Remaining: <strong><?= $remaining ?></strong></div>
    </div>
  </div>

  <script src="script.js"></script>
</body>
</html>
