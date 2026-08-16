<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

require_once __DIR__ . '/../../backend/controller/profileController.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$currentUserId = (int)$_SESSION['user_id'];

$controller = new ProfileController();
$userId = $_SESSION['user_id'];
$errors = [];
$activeStep = 'step1';

$displayName = $controller->profileModel->getDisplayName($userId);

$generatedCode = '';
if (($_SESSION['user_role'] ?? '') !== 'matchmaker') {
    $generatedCode = $controller->linkingModel->getOrCreateLinkCode($currentUserId);
}

// Handle POST submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role = $_POST['userRole'] ?? '';

    // AJAX Handler for regenerate code
    if (isset($_POST['action']) && $_POST['action'] === 'regenerateCode') {
        $newCode = $controller->linkingModel->generateNewCode($userId);
        echo json_encode(['code' => $newCode]);
        exit();
    }

    if ($role === 'single') {
        $result = $controller->processSingleSetup($userId, $_POST, $_FILES);
        if (isset($result['error'])) {
            $errors[] = $result['error'];
            $activeStep = $result['step'] ?? 'sStep2';
        }
    } elseif ($role === 'matchmaker') {
        $result = $controller->processMatchmakerSetup($userId, $_POST, $_FILES);
        if (isset($result['error'])) {
            $errors[] = $result['error'];
            $activeStep = $result['step'] ?? 'mStep2';
        }
    }
}
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
                    <option value="" disabled selected>Gender / Identity</option>
                    <option value="Woman">Woman</option>
                    <option value="Trans Woman">Trans Woman</option>
                    <option value="Non-Binary">Non-Binary (Sapphic)</option>
                    <option value="Queer">Queer</option>
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
                    <option value="" disabled selected>Choose Your Primary Hobby</option>
                    <option value="art">Art & Creative</option>
                    <option value="thrifting">Thrifting & Vintage</option>
                    <option value="plants">Plants & Gardening</option>
                    <option value="outdoors">Hiking & Camping</option>
                    <option value="reading">Reading & Bookstores</option>
                    <option value="music">Music & Concerts</option>
                    <option value="travel">Travel</option>
                    <option value="fitness">Fitness & Sports</option>
                    <option value="cooking">Cooking & Baking</option>
                    <option value="gaming">Gaming</option>
                    <option value="coffee">Coffee & Cafe Hopping</option>
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
                    <option value="" disabled selected>Interested In</option>
                    <option value="Women">Women</option>
                    <option value="Trans Women">Trans Women</option>
                    <option value="Non-Binary Sapphics">Non-Binary Sapphics</option>
                    <option value="All Sapphics">All Sapphic Women</option>
                </select>
            </div>

            <h3>THE HOOK</h3>
            <small>What attracts you in a potential partner?</small>
            <div class="field">
                <input type="text" name="hook" placeholder="Loves coffee dates, thrifting and spontaneous road trips">
            </div>

            <h3>MY BIO</h3>
            <small>This will be displayed to potential matches</small>
            <div class="field">
                <input type="text" name="bio" placeholder="Looking for someone to swap vinyl records with">
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
                <input type="file" id="mainProfileInput" name="profilePhoto" accept="image/*" class="uploadInput" onchange="showFileName(this, this.closest('.uploadBox'))">
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
                <input type="file" name="matchmakerProfilePhoto" accept="image/*" class="uploadInput" onchange="showFileName(this, this.closest('.uploadBox'))">
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
        const formData = new FormData();
        formData.append('action', 'regenerateCode');

        fetch('setupProfile.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.code) {
                document.getElementById('codeDisplay').textContent = data.code.split('').join(' ');
            }
        })
        .catch(err => console.error('Error regenerating code:', err));
    }

    // Reopen to active step if a submission error occurs
    goToStep('<?= $activeStep ?>');
</script>

</body>
</html>