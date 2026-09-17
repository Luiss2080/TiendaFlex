<!-- Modal Search -->
<div class="modal fade bg-white" id="templatemo_search" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="w-100 pt-1 mb-5 text-right">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="<?php echo View::url('search'); ?>" method="get" class="modal-content modal-body border-0 p-0">
            <div class="input-group mb-2">
                <input type="text" class="form-control" id="inputModalSearch" name="q" placeholder="Search ...">
                <button class="input-group-text bg-success text-light" type="submit">
                    <i class="fa fa-fw fa-search"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Start Content Page -->
<div class="container-fluid bg-light py-5">
    <div class="col-md-6 m-auto text-center">
        <h1 class="h1">Contact Us</h1>
        <p>
            Proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
            Lorem ipsum dolor sit amet.
        </p>
    </div>
</div>

<!-- Start Map -->
<div id="mapid" style="width: 100%; height: 300px;">
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7302.453092836291!2d90.47477022812872!3d23.77494577893369!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755c62fce7d991f%3A0xacfaf1ac8e944c5a!2sBasundhara%20R%2FA%2C%20Dhaka%201229%2C%20Bangladesh!5e0!3m2!1sen!2snl!4v1615214915308!5m2!1sen!2snl" width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
</div>
<!-- End Map -->

<!-- Start Contact -->
<div class="container py-5">
    <div class="row py-5">
        <form class="col-md-9 m-auto" method="post" action="/contact/send" role="form">
            <input type="hidden" name="csrf_token" value="<?php echo Session::getCsrf(); ?>">
            
            <?php if (Session::hasFlash('success')): ?>
                <div class="alert alert-success">
                    <?php echo htmlspecialchars(Session::flash('success'), ENT_QUOTES, 'UTF-8'); ?>
                </div>
            <?php endif; ?>

            <?php if (Session::hasFlash('error')): ?>
                <div class="alert alert-danger">
                    <?php echo htmlspecialchars(Session::flash('error'), ENT_QUOTES, 'UTF-8'); ?>
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="form-group col-md-6 mb-3">
                    <label for="inputname">Name</label>
                    <input type="text" class="form-control mt-1" id="name" name="name" placeholder="Name" value="<?php echo htmlspecialchars(Session::get('old_name', ''), ENT_QUOTES, 'UTF-8'); ?>" required>
                </div>
                <div class="form-group col-md-6 mb-3">
                    <label for="inputemail">Email</label>
                    <input type="email" class="form-control mt-1" id="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars(Session::get('old_email', ''), ENT_QUOTES, 'UTF-8'); ?>" required>
                </div>
            </div>
            <div class="mb-3">
                <label for="inputsubject">Subject</label>
                <input type="text" class="form-control mt-1" id="subject" name="subject" placeholder="Subject" value="<?php echo htmlspecialchars(Session::get('old_subject', ''), ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>
            <div class="mb-3">
                <label for="inputmessage">Message</label>
                <textarea class="form-control mt-1" id="message" name="message" placeholder="Message" rows="8" required><?php echo htmlspecialchars(Session::get('old_message', ''), ENT_QUOTES, 'UTF-8'); ?></textarea>
            </div>
            <div class="row">
                <div class="col text-end mt-2">
                    <button type="submit" class="btn btn-success btn-lg px-3">Let's Talk</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- End Contact -->

<!-- Start Contact Info -->
<div class="container-fluid bg-light py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-4 py-3">
                <div class="text-center">
                    <i class="fa fa-map-marker-alt fa-2x text-success mb-3"></i>
                    <h2 class="h5">Address</h2>
                    <p>123 Consectetur at ligula 10660</p>
                </div>
            </div>
            <div class="col-md-4 py-3">
                <div class="text-center">
                    <i class="fa fa-phone fa-2x text-success mb-3"></i>
                    <h2 class="h5">Phone</h2>
                    <p><a href="tel:+13055843456">010-020-0340</a></p>
                </div>
            </div>
            <div class="col-md-4 py-3">
                <div class="text-center">
                    <i class="fa fa-envelope fa-2x text-success mb-3"></i>
                    <h2 class="h5">Email</h2>
                    <p><a href="mailto:info@company.com">info@company.com</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Contact Info -->