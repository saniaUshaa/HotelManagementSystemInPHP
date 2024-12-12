<form action="/DBProject/login.php" method="post">
  <div class="row mb-3">
    <label for="username" class="col-sm-2 col-form-label">Username</label>
    <div class="col-sm-10">
      <input type="name" class="form-control" id="username" name="username" required>
    </div>
  </div>
  <div class="row mb-3" style="margin-top:30px;">
    <label for="password" class="col-sm-2 col-form-label">Password</label>
    <div class="col-sm-10">
      <input type="password" class="form-control" id="password" name="password" required>
    </div>
  </div>
  <div style="display:flex; align-items:center; justify-content:center; margin-top:40px">
    <button type="submit" class="btn btn-primary" style="width:200px">Login in</button>
  </div>
</form>