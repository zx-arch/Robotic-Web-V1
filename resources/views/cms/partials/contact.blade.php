<section id="contact" class="contact">
    <div class="container" data-aos="fade-up">

        <div class="section-title">
            <h2>Contact</h2>
            <p>Pertanyaan mengenai Artech, penyelenggaraan event dari Intan Pariwara atau informasi login dsb yang berkaitan robotic dapat menghubungi kontak dibawah ini atau dapat mengisikan form yang tertera.</p>
        </div>

        <div class="row">

            <div class="col-lg-5 d-flex align-items-stretch">

                <div class="info">
                    <div class="address" onclick="window.location.href=`https://maps.app.goo.gl/mixXKjqBWJdjmdBk7`">
                        <i class="bi bi-geo-alt"></i>
                        <h4>Location:</h4>
                        <p>PT Intan Pariwara</p>
                    </div>

                    <div class="email">
                        <i class="bi bi-envelope"></i>
                        <h4>Email:</h4>
                        <p><a href="mailto:bo.dedinugroho@intanpariwara.com">bo.dedinugroho@intanpariwara.com</a></p>
                    </div>

                    <div class="phone">
                        <i class="bi bi-phone"></i>
                        <h4>Whatsapp:</h4>
                        <p><a href="https://web.whatsapp.com/send/?phone=6285725320311&text&type=phone_number&app_absent=0">+ 62857-2532-0311</a></p>
                    </div>

                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15815.688362498151!2d110.60464108715819!3d-7.6915104999999855!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a412c170759ef%3A0x79d5480bd9d57ce6!2sPT.%20Intan%20Pariwara!5e0!3m2!1sen!2sid!4v1710927203173!5m2!1sen!2sid" style="border:0; width: 100%; height: 290px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>                    </div>

                </div>

                <div class="col-lg-7 mt-5 mt-lg-0 d-flex align-items-stretch">

                    <form action="{{route('dashboard.submit_chat')}}" method="post" role="form" class="php-email-form" id="contactForm">
                        @csrf

                        <div class="row">
                            @if (session()->has('success_submit_chat'))
                                <p class="text-success">{{session('success_submit_chat')}}</p>
                            @endif

                            @if (session()->has('error_submit_chat'))
                                <p class="text-danger">{{session('error_submit_chat')}}</p>
                            @endif
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="name">Your Name</label>
                                <input type="text" name="name" class="form-control" id="name" required>
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="email">Your Email</label>
                                <input type="email" class="form-control" name="email" id="email" required>
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="form-group">
                                <label for="subject">Subject</label>
                                <input type="text" class="form-control" name="subject" id="subject" required>
                                <div class="invalid-feedback"></div>
                            </div>
                            
                            <div class="form-group">
                                <label for="message">Message</label>
                                <textarea class="form-control" name="message" id="message" rows="10" required></textarea>
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="text-center">
                                <button type="submit" id="buttonSubmit">Send Message</button>
                            </div>
                            
                            <div class="my-3">
                                <div class="error-message">{{session()->has('error_submit_chat') ? session('error_submit_chat') : ''}}</div>
                                <div class="sent-message">{{session()->has('success_submit_chat') ? 'Your message has been sent. Thank you!' : ''}}</div>
                            </div>
                        </div>

                        <div class="loading">Loading</div>
                    </form>
                </div>

            </div>
        </div>

    </div>
    
</section>

<script>
    const form = document.getElementById('contactForm');
    const buttonSubmit = document.getElementById('buttonSubmit');
    var errorMessageDiv = document.querySelector('.error-message');

    // Periksa apakah elemen error-message memiliki konten
    if (errorMessageDiv.innerHTML.trim() !== '') {
        // Dapatkan elemen col-xl-4 pertama
        var firstCol = document.getElementById('contact');
                
        if (firstCol) {
            firstCol.scrollIntoView({ behavior: 'smooth' });
        }
    }

    form.addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent default form submission

        // Reset styles and error messages
        const inputs = form.querySelectorAll('input, textarea');
        inputs.forEach(input => {
            input.classList.remove('is-invalid');
            input.classList.remove('is-valid');
            input.nextElementSibling.textContent = '';
        });

        // Validate each input
        let isValid = true;
        inputs.forEach(input => {
            if (!input.checkValidity()) {
                isValid = false;
                input.classList.add('is-invalid');
                input.nextElementSibling.textContent = input.validationMessage;
            } else {
                input.classList.add('is-valid');
            }
        });

        if (isValid) {
            // If form is valid, you can submit it here
            form.submit();
        }
    });

    // Add event listener to validate on input change
    const inputFields = form.querySelectorAll('input, textarea');

    inputFields.forEach(input => {
        input.addEventListener('keyup', function() {
            if (!this.checkValidity()) {
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
                this.nextElementSibling.textContent = this.validationMessage;
            
            } else if (this.id === 'name' && !/^[a-zA-Z]+$/.test(this.value)) {
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
                this.nextElementSibling.textContent = 'Name should only contain letters';
            
            } else if (this.id === 'email' && !isValidEmail(this.value)) {
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
                this.nextElementSibling.textContent = 'Please enter a valid email address';
            
            } else if (this.id === 'message' && this.value.trim().length < 10) {
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
                this.nextElementSibling.textContent = 'Message should have at least 10 characters';
            
            } else {
                if (this.id === 'name' && !/^[a-zA-Z]{3,}$/.test(input.value)) {
                    isValid = false;
                    input.classList.add('is-invalid');
                    input.classList.remove('is-valid');
                    input.nextElementSibling.textContent = 'Name should have at least 3 letters';
                } else {
                    this.classList.add('is-valid');
                    this.classList.remove('is-invalid');
                    this.nextElementSibling.textContent = '';
                }
            }

            // Disable submit button if form is not valid
            if (!form.checkValidity()) {
                buttonSubmit.disabled = true;
                buttonSubmit.style.pointerEvents = 'none';
                buttonSubmit.style.opacity = '0.5';
            } else {
                buttonSubmit.disabled = false;
                buttonSubmit.style.pointerEvents = 'auto';
                buttonSubmit.style.opacity = '1';
            }
        });
    });

    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
    
</script>

