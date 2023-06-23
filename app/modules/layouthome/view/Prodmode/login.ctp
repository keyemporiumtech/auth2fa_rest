	<div id="login">
        <h3 class="text-center text-white pt-5">Login</h3>
        <div class="container">
            <div id="login-row" class="row justify-content-center align-items-center">
                <div id="login-column" class="col-md-6">
                    <div id="login-box" class="col-md-12">
                        <form id="login-form" class="form" action="<?php echo Router::url(array ('controller' =>  'prodmode','action' => 'verify'))?>" method="post">
                            <h3 class="text-center">Login</h3>
                            <div class="form-group">
                                <label for="username" class="">Username:</label><br>
                                <input type="text" name="username" id="username" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="password">Password:</label><br>
                                <input type="text" name="password" id="password" class="form-control">
                            </div>
                            <div class="form-group">                                
                                <input type="submit" name="submit" class="btn btn-md btn-primary" value="submit">
                            </div>                            
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>