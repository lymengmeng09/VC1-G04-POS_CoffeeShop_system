<section class="h-100 gradient-form" style="background-color: #eee;">
  <div class="container h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-xl-10">
        <div class="card rounded-3 text-black">
          <div class="row g-0">
            <div class="col-lg-6">
              <div class="card-body p-md-5 mx-md-4">

                <div class="text-center">
                  <img src="/views/assets/images/logo.png"
                    style="width: 185px;" alt="logo">
                </div>

                <form method="POST" id="loginForm" class="needs-validation" novalidate>
                  <p class="text-center">Please login to your account</p>
                  <div data-mdb-input-init class="form-outline mb-2">
                    <label class="form-label" for="form2Example11">Email</label>
                    <input type="text" id="form2Example11" class="form-control" name="email"
                      placeholder="your email" required />
                    <div class="invalid-feedback">
                      Email is required.
                    </div>
                  </div>
                  <div data-mdb-input-init class="form-outline mb-2">
                    <label class="form-label" for="form2Example22">Password</label>
                    <input type="password" name="password" id="form2Example22" class="form-control" placeholder="password" required />
                    <div class="invalid-feedback">
                      Password is required.
                    </div>
                  </div>
                  <div class="text-center pt-1 mb-4 pb-1">
                    <button class="btn text-light fa-lg gradient-custom-2 mb-2" type="submit">Log
                      in</button><br>
                    <a class="text-muted" href="#!">Forgot password?</a>
                  </div>
                  <p class="text-center"><a href="/login/register">Don't have an account? Register</a></p>
                </form>

              </div>
            </div>
            <div class="col-lg-6 d-flex align-items-center gradient-custom-2" style="background-image: url(views/assets/images/cofe.png)">
              <div class="text-white px-3 py-4 p-md-5 mx-md-4" style="background: #421f128d;">
                <h4 class="mb-4">Login to Your Account</h4>
                <p class="small mb-0">Sign up now to access your coffee shop management system. Easily manage inventory, track sales, and streamline operations with our user-friendly platform.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>