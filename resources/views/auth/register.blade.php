<x-guest-layout>
    <div class="card wizard-card">
        <div class="row g-0 h-100">

            <!-- Left Side: Wizard Form -->
            <div class="col-lg-6 form-side">

                <!-- Wizard Steps -->
                <div class="wizard-steps">
                    <div class="wizard-step active" data-step="1">
                        <div class="step-circle">1</div>
                        <div class="step-label">Personal Info</div>
                    </div>
                    <div class="wizard-step" data-step="2">
                        <div class="step-circle">2</div>
                        <div class="step-label">Address</div>
                    </div>
                    <div class="wizard-step" data-step="3">
                        <div class="step-circle">3</div>
                        <div class="step-label">Security</div>
                    </div>
                </div>

                <!-- Heading -->
                <h2 class="welcome-heading">Create Account</h2>

                <form method="POST" action="{{ route('register') }}" id="wizardForm">
                    @csrf

                    <!-- Step 1: Personal Info -->
                    <div class="wizard-content active" id="step1">

                        <!-- Name -->
                        <div class="mb-2-custom">
                            <label for="name" class="form-label-custom">Full Name</label>
                            <div class="input-group-custom">
                                <div class="input-icon-box">
                                    <i class="bi bi-person"></i>
                                </div>
                                <input type="text"
                                    class="form-control-custom @error('name') is-invalid @enderror"
                                    id="name"
                                    name="name"
                                    value="{{ old('name') }}"
                                    placeholder="John Doe"
                                    required
                                    autofocus>
                            </div>
                            @error('name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-2-custom">
                            <label for="email" class="form-label-custom">Email Address</label>
                            <div class="input-group-custom">
                                <div class="input-icon-box">
                                    <i class="bi bi-envelope"></i>
                                </div>
                                <input type="email"
                                    class="form-control-custom @error('email') is-invalid @enderror"
                                    id="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    placeholder="john@example.com"
                                    required>
                            </div>
                            @error('email')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Mobile -->
                        <div class="mb-2-custom">
                            <label for="mobile" class="form-label-custom">Mobile Number</label>
                            <div class="input-group-custom">
                                <div class="input-icon-box">
                                    <i class="bi bi-telephone"></i>
                                </div>
                                <input type="tel"
                                    class="form-control-custom @error('mobile') is-invalid @enderror"
                                    id="mobile"
                                    name="mobile"
                                    value="{{ old('mobile') }}"
                                    maxlength="10"
                                    placeholder="9876543210"
                                    required>
                            </div>
                            @error('mobile')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Gender -->
                        <div class="mb-2-custom">
                            <label for="gender" class="form-label-custom">Gender</label>
                            <div class="input-group-custom">
                                <div class="input-icon-box">
                                    <i class="bi bi-people"></i>
                                </div>
                                <select class="form-control-custom @error('gender') is-invalid @enderror"
                                    id="gender"
                                    name="gender">
                                    <option value="">Select Gender</option>
                                    <option value="Male" {{ old('gender')=='Male'?'selected':'' }}>Male</option>
                                    <option value="Female" {{ old('gender')=='Female'?'selected':'' }}>Female</option>
                                    <option value="Other" {{ old('gender')=='Other'?'selected':'' }}>Other</option>
                                </select>
                            </div>
                            @error('gender')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Date of Birth -->
                        <div class="mb-2-custom">
                            <label for="dob" class="form-label-custom">Date of Birth</label>
                            <div class="input-group-custom">
                                <div class="input-icon-box">
                                    <i class="bi bi-calendar"></i>
                                </div>
                                <input type="date"
                                    class="form-control-custom @error('dob') is-invalid @enderror"
                                    id="dob"
                                    name="dob"
                                    value="{{ old('dob') }}"
                                    required>
                            </div>
                            @error('dob')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="wizard-nav">
                            <div></div>
                            <button type="button" class="btn btn-wizard btn-wizard-next" onclick="nextStep()">
                                Next <i class="bi bi-arrow-right ms-1"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Step 2: Address -->
                    <div class="wizard-content" id="step2">

                        <!-- Address -->
                        <div class="mb-2-custom">
                            <label for="address" class="form-label-custom">Address</label>
                            <div class="input-group-custom">
                                <div class="input-icon-box">
                                    <i class="bi bi-house"></i>
                                </div>
                                <input type="text"
                                    class="form-control-custom @error('address') is-invalid @enderror"
                                    id="address"
                                    name="address"
                                    value="{{ old('address') }}"
                                    placeholder="123 Main Street"
                                    required>
                            </div>
                            @error('address')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- City -->
                        <div class="mb-2-custom">
                            <label for="city" class="form-label-custom">City</label>
                            <div class="input-group-custom">
                                <div class="input-icon-box">
                                    <i class="bi bi-building"></i>
                                </div>
                                <input type="text"
                                    class="form-control-custom @error('city') is-invalid @enderror"
                                    id="city"
                                    name="city"
                                    value="{{ old('city') }}"
                                    placeholder="Dehradun"
                                    required>
                            </div>
                            @error('city')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- State -->
                        <div class="mb-2-custom">
                            <label for="state" class="form-label-custom">State</label>
                            <div class="input-group-custom">
                                <div class="input-icon-box">
                                    <i class="bi bi-geo-alt"></i>
                                </div>
                                <input type="text"
                                    class="form-control-custom @error('state') is-invalid @enderror"
                                    id="state"
                                    name="state"
                                    value="{{ old('state') }}"
                                    placeholder="Uttarakhand"
                                    required>
                            </div>
                            @error('state')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Country -->
                        <div class="mb-2-custom">
                            <label for="country" class="form-label-custom">Country</label>
                            <div class="input-group-custom">
                                <div class="input-icon-box">
                                    <i class="bi bi-globe"></i>
                                </div>
                                <input type="text"
                                    class="form-control-custom @error('country') is-invalid @enderror"
                                    id="country"
                                    name="country"
                                    value="{{ old('country', 'India') }}"
                                    placeholder="India"
                                    required>
                            </div>
                            @error('country')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Pincode -->
                        <div class="mb-2-custom">
                            <label for="pincode" class="form-label-custom">Pincode</label>
                            <div class="input-group-custom">
                                <div class="input-icon-box">
                                    <i class="bi bi-mailbox"></i>
                                </div>
                                <input type="text"
                                    class="form-control-custom @error('pincode') is-invalid @enderror"
                                    id="pincode"
                                    name="pincode"
                                    value="{{ old('pincode') }}"
                                    maxlength="6"
                                    placeholder="248001"
                                    required>
                            </div>
                            @error('pincode')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="wizard-nav">
                            <button type="button" class="btn btn-wizard btn-wizard-prev" onclick="prevStep()">
                                <i class="bi bi-arrow-left me-1"></i> Back
                            </button>
                            <button type="button" class="btn btn-wizard btn-wizard-next" onclick="nextStep()">
                                Next <i class="bi bi-arrow-right ms-1"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Step 3: Security -->
                    <div class="wizard-content" id="step3">

                        <!-- Password -->
                        <div class="mb-2-custom">
                            <label for="password" class="form-label-custom">Password</label>
                            <div class="input-group-custom">
                                <div class="input-icon-box">
                                    <i class="bi bi-lock"></i>
                                </div>
                                <input type="password"
                                    class="form-control-custom @error('password') is-invalid @enderror"
                                    id="password"
                                    name="password"
                                    placeholder="Minimum 8 characters"
                                    required>
                            </div>
                            @error('password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-2-custom">
                            <label for="password_confirmation" class="form-label-custom">Confirm Password</label>
                            <div class="input-group-custom">
                                <div class="input-icon-box">
                                    <i class="bi bi-shield-check"></i>
                                </div>
                                <input type="password"
                                    class="form-control-custom"
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    placeholder="Re-enter password"
                                    required>
                            </div>
                        </div>

                        <div class="wizard-nav">
                            <button type="button" class="btn btn-wizard btn-wizard-prev" onclick="prevStep()">
                                <i class="bi bi-arrow-left me-1"></i> Back
                            </button>
                            <button type="submit" class="btn btn-wizard btn-wizard-next">
                                <i class="bi bi-box-arrow-in-right me-1"></i> Register
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Login Link -->
                <div class="text-center mt-2">
                    <span class="signup-note">Already Registered? </span>
                    <a href="{{ route('login') }}" class="link-signup">Log in</a>
                </div>
            </div>

            <!-- Right Side: Info Panel -->
            <div class="col-lg-6 info-side">
                <div class="info-panel-content">

                    <!-- Brand Name -->
                    <h1 class="brand-name">Your next journey starts here</h1>

                    <!-- Description -->
                    <p class="brand-desc">
                        Create an account to save your details and make planning a Uttarakhand getaway simpler.
                    </p>

                    <!-- Features -->
                    <div class="feature-item">
                        <div class="feature-icon-box">
                            <i class="bi bi-check-lg"></i>
                        </div>
                        <span class="feature-text">Easy Account Setup</span>
                    </div>

                    <div class="feature-item">
                        <div class="feature-icon-box">
                            <i class="bi bi-shield-fill"></i>
                        </div>
                        <span class="feature-text">Secure & Reliable Platform</span>
                    </div>

                    <div class="feature-item">
                        <div class="feature-icon-box">
                            <i class="bi bi-lightning-fill"></i>
                        </div>
                        <span class="feature-text">Real-time Updates</span>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Wizard JavaScript -->
    <script>
        let currentStep = 1;
        const totalSteps = 3;

        function nextStep() {
            // Validate current step
            if (currentStep === 1) {
                const name = document.getElementById('name').value;
                const email = document.getElementById('email').value;
                const mobile = document.getElementById('mobile').value;
                const gender = document.getElementById('gender').value;
                const dob = document.getElementById('dob').value;

                if (!name || !email || !mobile || !gender || !dob) {
                    alert('Please fill all fields before proceeding.');
                    return;
                }
            } else if (currentStep === 2) {
                const address = document.getElementById('address').value;
                const city = document.getElementById('city').value;
                const state = document.getElementById('state').value;
                const country = document.getElementById('country').value;
                const pincode = document.getElementById('pincode').value;

                if (!address || !city || !state || !country || !pincode) {
                    alert('Please fill all address fields before proceeding.');
                    return;
                }
            }

            // Hide current step
            document.getElementById('step' + currentStep).classList.remove('active');

            // Update step indicator
            document.querySelector('[data-step="' + currentStep + '"]').classList.remove('active');
            document.querySelector('[data-step="' + currentStep + '"]').classList.add('completed');

            // Move to next step
            currentStep++;

            // Show next step
            document.getElementById('step' + currentStep).classList.add('active');
            document.querySelector('[data-step="' + currentStep + '"]').classList.add('active');
        }

        function prevStep() {
            // Hide current step
            document.getElementById('step' + currentStep).classList.remove('active');

            // Update step indicator
            document.querySelector('[data-step="' + currentStep + '"]').classList.remove('active');

            // Move to previous step
            currentStep--;

            // Show previous step
            document.getElementById('step' + currentStep).classList.add('active');
            document.querySelector('[data-step="' + currentStep + '"]').classList.remove('completed');
            document.querySelector('[data-step="' + currentStep + '"]').classList.add('active');
        }
    </script>
</x-guest-layout>
