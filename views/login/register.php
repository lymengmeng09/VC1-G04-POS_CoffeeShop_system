<section class="h-100 gradient-form" style="background-color: #eee;">
  <div class="container py-5 h-100">
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

                <form method="POST">
                  <p class="text-center">Create a new account</p>

                  <div class="form-outline mb-3">
                    <label class="form-label" for="name">Full Name</label>
                    <input type="text" id="name" class="form-control" name="name"
                      placeholder="Your full name" required />
                  </div>

                  <div class="form-outline mb-3">
                    <label class="form-label" for="email">Email</label>
                    <input type="email" id="email" class="form-control" name="email"
                      placeholder="Your email address" required />
                  </div>

                  <div class="form-outline mb-3">
                    <label class="form-label" for="password">Password</label>
                    <input type="password" name="password" id="password" class="form-control" 
                      placeholder="Create a password" required />
                  </div>

                  <div class="text-center pt-1 mb-3 pb-1">
                    <button class="btn text-light fa-lg gradient-custom-2" type="submit">Register</button>
                  </div>
                  
                  <p class="mt-2 text-center"><a href="/login">Already have an account? Login</a></p>
                </form>

              </div>
            </div>
            <div class="col-lg-6 d-flex align-items-center gradient-custom-2" style="background-image: url(/views/assets/images/cofe.png);">
              <div class="text-white px-3 py-4 p-md-5 mx-md-4" style="background: #421f128d;">
                <h4 class="mb-4">Join our Coffee Shop</h4>
                <p class="small mb-0">Create an account to access our coffee shop management system. Manage inventory, track sales, and more with our easy-to-use platform.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

