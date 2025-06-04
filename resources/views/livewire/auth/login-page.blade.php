<div class="auth-box">
    <div id="loginform">
        <div class="logo">
            <span class="db"><img src="{{asset('sbAdmin/img/unimed.png')}}" width="30%" alt="logo" /></span>
            <br><br>
            <h5 class="font-medium m-b-20">Sign In to Admin</h5>
        </div>
        <!-- Form -->
        <div class="row">
            <div class="col-12">
                <form class="form-horizontal m-t-20" id="loginform" wire:submit.prevent="login">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1"><i class="ti-user"></i></span>
                        </div>
                        <input type="text" wire:model="email"  class="form-control form-control-lg" placeholder="Email" aria-label="Username" aria-describedby="basic-addon1" required>
                        @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon2"><i class="ti-pencil"></i></span>
                        </div>
                        <input wire:model="password" type="password" class="form-control form-control-lg" placeholder="Password" aria-label="Password" aria-describedby="basic-addon1" required>
                        @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="form-group text-center">
                        <div class="col-xs-12 p-b-20">
                            <button class="btn btn-block btn-lg btn-info" type="submit">Log In</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


