<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

// Redirect if not logged in
if (!isset($_SESSION['userId'])) {
    header("Location: index.php");
    exit();
}

// Loads the backend controller for profiles
require_once __DIR__ . '/../../backend/controller/profileController.php';

$profileCtrl = new ProfileController();
$userId = $_SESSION['userId'];
$errors = [];
$activeStep = 'step1';

// Form handling 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role = $_POST['userRole'] ?? '';

    if ($role === 'single') {
        $res = $profileCtrl->processSingleSetup($userId, $_POST, $_FILES);
        if (isset($res['error'])) {
            $errors[] = $res['error'];
            $activeStep = $res['step'];
        }
    } elseif ($role === 'matchmaker') {
        $res = $profileCtrl->processMatchmakerSetup($userId, $_POST, $_FILES);
        if (isset($res['error'])) {
            $errors[] = $res['error'];
            $activeStep = $res['step'];
        }
    }
}

// Fetch display data
$displayName = $profileCtrl->profileModel->getDisplayName($userId);
$generatedCode = $profileCtrl->linkingModel->ensureCode($userId);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vouch Profile Set Up</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Google+Sans+Flex:opsz,wght@6..144,1..1000&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="setupProfile.css?v=2">
</head>

<body class="<?= ($activeStep === 'mStep2' || $activeStep === 'mStep3') ? 'matchmakerMode' : '' ?>">

<div class="card">

    <div class="artPanel">
        <div class="artFiller">
            <div class="logo">
                <img src="../assets/images/blackLogo.png" alt="vouch logo">
            </div>
            <div class="artTagline">
                WELCOME <span class="highlight"><?= strtoupper(htmlspecialchars($displayName)) ?></span><br>
                SET UP YOUR PROFILE.
            </div>
        </div>
    </div>

    <div class="formPanel">

    <?php if (!empty($errors)): ?>
        <div class="alert alertError">
            <?= htmlspecialchars(implode(' ', $errors)) ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="setupProfile.php" enctype="multipart/form-data" id="setupForm">
        <input type="hidden" name="userRole" id="userRoleInput">





        <!-- Role selection -->
        <div class="stepBlock" id="step1">
            <div class="logoInline">
                <img src="../assets/images/blackLogo.png" alt="vouch logo">
            </div>
            <h1>Let's Get Started!</h1>

            <div class="roleChoices">
                <button type="button" class="roleBtn btnSingle" onclick="chooseRole('single')">I AM LOOKING FOR LOVE</button>
                <button type="button" class="roleBtn btnMatchmaker" onclick="chooseRole('matchmaker')">I AM THE MATCHMAKER</button>
            </div>
        </div>





        <!-- Single step 2 - My details -->
        <div class="stepBlock" id="sStep2">
            <div class="logoInline">
                <img src="../assets/images/blackLogo.png" alt="vouch logo">
            </div>
            <h1>Let's Get Started!</h1>
            <h3>MY DETAILS</h3>

            <div class="fieldRow">
                <select name="birthYear" class="fieldHalf">
                    <option value="" disabled selected>Birth Year</option>
                    <?php for ($y = 2006; $y >= 1950; $y--): ?>
                    <option value="<?= $y ?>"><?= $y ?></option>
                    <?php endfor; ?>
                </select>

                <select name="gender" class="fieldHalf">
                    <option value="" disabled selected>Gender</option>
                    <option value="Female">Female</option>
                    <option value="Male">Male</option>
                    <option value="Transgender">Transgender</option>
                    <option value="Non-Binary">Non-Binary</option>
                </select>
            </div>

            <div class="field">
                <input type="text" name="location" placeholder="Location (City, Country)">
            </div>
            <div class="field">
                <input type="text" name="occupation" placeholder="Occupation">
            </div>
            <div class="field">
                <select name="hobbies">
                    <option value="" disabled selected>Choose Your Hobby</option>
                    <option value="sports">Sports</option>
                    <option value="art">Art</option>
                    <option value="travel">Travel</option>
                    <option value="music">Music</option>
                    <option value="gaming">Gaming</option>
                    <option value="fitness">Fitness</option>
                    <option value="movies">Movies</option>
                    <option value="reading">Reading</option>
                    <option value="cooking">Cooking</option>
                    <option value="technology">Technology</option>
                    <option value="fashion">Fashion</option>
                    <option value="photography">Photography</option>
                    <option value="outdoors">Hiking</option>
                    <option value="other">Other</option>
                </select>
            </div>

            <div class="navActions">
                <button type="button" class="navBtn" onclick="goToStep('step1')">
                    <img src="../assets/images/arrowIcon.png" alt="Previous" class="arrowIcon">
                    <span>Previous Page</span>
                </button>

                <button type="button" class="navBtn" onclick="goToStep('sStep3')">
                    <span>Next Page</span>
                    <img src="../assets/images/arrowIcon.png" alt="Next" class="arrowIcon arrowRight">
                </button>
            </div>
        </div>





        <!-- Single step 3 - Dating preferences & bio -->
        <div class="stepBlock" id="sStep3">
            <div class="logoInline">
                <img src="../assets/images/blackLogo.png" alt="vouch logo">
            </div>
            <h1>Let's Get Started!</h1>
            <h3>DATING PREFERENCES</h3>

            <div class="fieldRow">
                <select name="targetAges" class="fieldHalf">
                    <option value="" disabled selected>Age Range</option>
                    <option value="18-25">18 - 25</option>
                    <option value="26-35">26 - 35</option>
                    <option value="36-45">36 - 45</option>
                    <option value="46-55">46 - 55</option>
                    <option value="56+">56+</option>
                </select>
                <select name="targetGender" class="fieldHalf">
                    <option value="" disabled selected>Gender</option>
                    <option value="Men">Men</option>
                    <option value="Women">Women</option>
                    <option value="Everyone">Everyone</option>
                </select>
            </div>

            <h3>THE HOOK</h3>
            <small>What are you looking for in a partner...</small>
            <div class="field">
                <input type="text" name="hook" placeholder="Tall, funny and a finance bro">
            </div>

            <h3>MY BIO</h3>
            <small>This will be displayed to potential matches</small>
            <div class="field">
                <input type="text" name="bio" placeholder="Looking for someone to share fries with">
            </div>

            <div class="navActions">
                <button type="button" class="navBtn" onclick="goToStep('sStep2')">
                    <img src="../assets/images/arrowIcon.png" alt="Arrow Icon" class="arrowIcon">
                    Previous Page
                </button>
                <button type="button" class="navBtn" onclick="goToStep('sStep4')">
                    Next Page
                    <img src="../assets/images/arrowIcon.png" alt="Arrow Icon" class="arrowIcon arrowRight">
                </button>
            </div>
        </div>





        <!-- Single step 4 - Photos -->
        <div class="stepBlock" id="sStep4">
            <div class="logoInline">
                <img src="../assets/images/blackLogo.png" alt="vouch logo">
            </div>
            <h1>Let's Get Started!</h1>

            <h3>PROFILE PHOTO</h3>
            <label class="uploadBox">
                <div class="uploadIcon">
                    <img src="../assets/images/uploadIcon.png" alt="Upload Icon">
                </div>
                <span class="uploadLabel">Click to Upload</span>
                <input type="file" name="profilePhoto" accept="image/*" class="uploadInput" onchange="showFileName(this, this.closest('.uploadBox'))">
            </label>

            <h3>GALLERY</h3>
            <small>Upload up to 5 photos of yourself</small>
            <label class="uploadBox">
                <div class="uploadIcon">
                    <img src="../assets/images/uploadIcon.png" alt="Upload Icon">
                </div>
                <span class="uploadLabel">Click to Upload</span>
                <input type="file" name="galleryPhotos[]" accept="image/*" multiple class="uploadInput" onchange="showFileName(this, this.closest('.uploadBox'))">
            </label>

            <div class="navActions">
                <button type="button" class="navBtn" onclick="goToStep('sStep3')">
                    <img src="../assets/images/arrowIcon.png" alt="Arrow Icon" class="arrowIcon">
                    Previous Page
                </button>
                <button type="button" class="navBtn" onclick="goToStep('sStep5')">
                    Next Page
                    <img src="../assets/images/arrowIcon.png" alt="Arrow Icon" class="arrowIcon arrowRight">
                </button>
            </div>
        </div>





        <!-- Single step 5 - Linking accounts-->
        <div class="stepBlock" id="sStep5">
            <div class="logoInline">
                <img src="../assets/images/blackLogo.png" alt="vouch logo">
            </div>
            <h1>Bring In Your Circle</h1>

            <h3>LINKING</h3>
            <small>Give your matchmaker this code to successfully link your profiles</small>

            <div class="codeDisplay" id="codeDisplay"><?= implode(' ', str_split($generatedCode)) ?></div>

            <button type="button" class="roleBtn btnSingle" id="regenerateBtn" onclick="regenerateCode()">
                REGENERATE CODE
            </button>

            <button type="submit" class="submitBtn">
                Save & Continue to Dashboard
            </button>

            <div class="navActions">
                <button type="button" class="navBtn" onclick="goToStep('sStep4')">
                    <img src="../assets/images/arrowIcon.png" alt="Arrow Icon" class="arrowIcon">
                    Previous Page
                </button>
            </div>
        </div>





        <!-- Matchmaker step 2 - Details -->
        <div class="stepBlock" id="mStep2">
            <div class="logoInline">
                <img src="../assets/images/blackLogo.png" alt="vouch logo">
            </div>
            <h1>Let's Get Started!</h1>
            <h3>MY DETAILS</h3>
            <div class="field">
                <select name="relationship">
                    <option value="" disabled selected>Relationship to Single</option>
                    <option value="Best Friend">Best Friend</option>
                    <option value="Friend">Friend</option>
                    <option value="Sibling">Sibling</option>
                    <option value="Parent">Parent</option>
                    <option value="Coworker">Coworker</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <h3>PROFILE PHOTO</h3>
            <label class="uploadBox">
                <div class="uploadIcon">
                    <img src="../assets/images/uploadIcon.png" alt="Upload Icon">
                </div>
                <span class="uploadLabel">Click to Upload</span>
                <input type="file" name="profilePhoto" accept="image/*" class="uploadInput" onchange="showFileName(this, this.closest('.uploadBox'))">
            </label>

            <h3>MY CREDENTIALS</h3>
            <small>Why are you the perfect Matchmaker for this profile?</small>
            <div class="field">
                <input type="text" name="credentials" placeholder="I've known her for 10 years and know her exact type">
            </div>

            <div class="navActions">
                <button type="button" class="navBtn" onclick="goToStep('step1')">
                    <img src="../assets/images/arrowIcon.png" alt="Arrow Icon" class="arrowIcon">
                    Previous Page
                </button>
                <button type="button" class="navBtn" onclick="goToStep('mStep3')">
                    Next Page
                    <img src="../assets/images/arrowIcon.png" alt="Arrow Icon" class="arrowIcon arrowRight">
                </button>
            </div>
        </div>





        <!-- Matchmaker step 3 - Linking accounts -->
        <div class="stepBlock" id="mStep3">
            <div class="logoInline">
                <img src="../assets/images/blackLogo.png" alt="vouch logo">
            </div>
            <h1>Bring In Your Circle</h1>
            <h3>LINKING ACCOUNT</h3>
            <small>Input the unique 5 digit code to successfully link your profiles together</small>

            <div class="field codeInputWrap">
                <input type="text" name="linkCode" maxlength="5" placeholder="0 0 0 0 0" id="codeInput" inputmode="numeric" pattern="\d{5}">
            </div>

            <button type="submit" class="submitBtn">
                Link Profiles
            </button>

            <div class="navActions">
                <button type="button" class="navBtn" onclick="goToStep('mStep2')">
                    <img src="../assets/images/arrowIcon.png" alt="Arrow Icon" class="arrowIcon">
                    Previous Page
                </button>
            </div>
        </div>

    </form>
    </div>
</div>

<script>
    function chooseRole(role) {
        document.getElementById('userRoleInput').value = role;

        if (role === 'matchmaker') {
            document.body.classList.add('matchmakerMode');
        } else {
            document.body.classList.remove('matchmakerMode');
        }
        goToStep(role === 'single' ? 'sStep2' : 'mStep2');
    }

    function goToStep(stepId) {
        document.querySelectorAll('.stepBlock').forEach(block => block.classList.remove('active'));

        const activeBlock = document.getElementById(stepId);
        if (activeBlock) {
            activeBlock.classList.add('active');
        }
        
        if (stepId === 'step1') {
            document.body.classList.remove('matchmakerMode');
        }
    }

    function showFileName(input, box) {
        const label = box.querySelector('.uploadLabel');
        if (input.files && input.files.length > 0) {
            label.textContent = input.files.length > 1
                ? input.files.length + ' files selected'
                : input.files[0].name;
        }
    }

    function regenerateCode() {
        fetch('../../backend/routes/regenerateCode.php')
            .then(res => res.json())
            .then(data => {
                if (data.code) {
                    document.getElementById('codeDisplay').textContent = data.code.split('').join(' ');
                }
            })
            .catch(err => console.error("Error regenerating code:", err));
    }

    // Reopen to active step if a submission error occurs
    goToStep('<?= $activeStep ?>');
</script>

</body>
</html>