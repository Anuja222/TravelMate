<!DOCTYPE html>
<html>
<head>
  <title>Create Destination</title>
  <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Admin/createDestination.css?v=<?= time() ?>">
  <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Admin/common.css?v=<?= time() ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
  <?php include __DIR__ . '/../traveller/header.view.php'; ?>

  <div class="page-containerr">
    <div class="content">
      <h1>Create Destination</h1>

      <form id="createDestForm">
        <div>
          <label>Title</label>
          <input name="title" id="title" required>
        </div>
        <div>
          <label>Slug (optional)</label>
          <input name="slug" id="slug">
        </div>
        <div>
          <label>Description</label>
          <textarea name="description" id="description"></textarea>
        </div>
        <div>
          <label>Image</label>
          <input type="file" name="image" id="image">
        </div>
        <div>
          <button type="submit" class="btn-primary">Create</button>
          <button type="button" onclick="window.location.href='ViewListing'">Cancel</button>
        </div>
      </form>
    </div>
  </div>

  <script>
  document.getElementById('createDestForm').addEventListener('submit', function(e){
    e.preventDefault();
    const fd = new FormData(this);
    fetch('../public/api/destination/create', { method:'POST', body: fd, credentials: 'same-origin' })
      .then(r => r.json())
      .then(resp => {
        if (resp.success) { alert('Created'); window.location.href='ViewListing'; }
        else alert(JSON.stringify(resp.errors));
      })
      .catch(err => { alert('Network error'); console.error(err); });
  });
  </script>
</body>
</html>
