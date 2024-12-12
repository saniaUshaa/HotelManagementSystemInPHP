<form style="margin-top:30px; display:flex; flex-direction:column;" action="/DBProject/signup.php" method="post">
<div class="row" style="margin-bottom:15px;">
<label for="firstname" class="col-sm-2 col-form-label">First Name</label>
  <div class="col">
    <input type="text" class="form-control" placeholder="First name" aria-label="firstname" name="firstname" id="firstname" required>
  </div>
  <label for="lastname" class="col-sm-2 col-form-label">Last Name</label>
  <div class="col">
    <input type="text" class="form-control" placeholder="Last name" aria-label="lastname" name="lastname" id="lastname" required>
  </div>
</div>
  <div class="row mb-3">
    <label for="password" class="col-sm-2 col-form-label">Password</label>
    <div class="col-sm-10">
      <input type="password" class="form-control" id="password" name=password required>
    </div>
  </div>
  <div class="row g-3">
  <div class="col-sm-7">
    <label for="inputCity3" class="col-sm-2 col-form-label">City</label>
    <input type="text" class="form-control" placeholder="City" aria-label="City">
  </div>
  <div class="col-sm">
    <label for="inputState3" class="col-sm-2 col-form-label">State</label>
    <input type="text" class="form-control" placeholder="State" aria-label="State">
  </div>
  <div class="col-sm">
  <label for="inputZip3" class="col-sm-2 col-form-label">Zip</label>
    <input type="text" class="form-control" placeholder="Zip" aria-label="Zip">
  </div>
  </div>
  <fieldset class="row mb-3" style="margin-top:17px;">
    <legend class="col-form-label col-sm-2 pt-0">User Type</legend>
    <div class="col-sm-10">
      <div class="form-check">
        <input class="form-check-input" type="radio" name="radio" id="radio1" value="admin" checked>
        <label class="form-check-label" for="radio1">
          Admin
        </label>
      </div>
      <div class="form-check">
        <input class="form-check-input" type="radio" name="radio" id="radio2" value="user">
        <label class="form-check-label" for="radio2">
          User
        </label>
      </div>
    </div>
  </fieldset>
  <div style="display:flex; align-items:center; justify-content:center; margin-top:20px">
    <button type="submit" class="btn btn-primary" style="width:200px">Sign in</button>
  </div>
</form>