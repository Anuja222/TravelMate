
<h2>Comments</h2>

<form method ="POST">
    <textarea name = "content" required placeholder="Write a comment..."></textarea>
    <button type ="submit">send</button>
</form>

<?php foreach($comments as $c): ?>
    <p><?= (htmlspecialchars($c['content']))?></p>
<?php endforeach; ?>